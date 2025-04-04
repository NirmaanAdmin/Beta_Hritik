<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MinutesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Load language file and models
        $this->lang->load('meeting_management', 'english');
        $this->load->model('Meeting_model');
        $this->load->model('Task_model');
        $this->load->model('Clients_model');  // Load the default clients model
    }

    // View and manage the minutes of a meeting
    public function index($agenda_id)
    {
        // Fetch the agenda and the minutes for this agenda
        $data['agenda'] = $this->Meeting_model->get_agenda($agenda_id);
        $data['minutes'] = $this->Meeting_model->get_minutes($agenda_id);
        $data['agenda_id'] = $agenda_id;  // Pass the agenda_id to the view

        // Fetch the list of tasks associated with this agenda
        $data['tasks'] = $this->Meeting_model->get_tasks_by_agenda($agenda_id);

        // Fetch the list of staff members for task assignment
        $this->load->model('Staff_model');
        $data['staff_members'] = $this->Staff_model->get();  // Load all staff members
        $data['clients'] = $this->Clients_model->get();      // Load all clients

        // Fetch selected participants
        $data['selected_participants'] = $this->Meeting_model->get_selected_participants($agenda_id) ?? [];  // Ensure it's always an array


        $data['attachments'] = $this->Meeting_model->get_meeting_attachments('agenda_meeting', $agenda_id);
        $data['title'] = _l('meeting_minutes');
        $data['other_participants'] = $this->Meeting_model->get_participants($agenda_id);
        // Load the minutes form view (with tasks form and task list added)
        $this->load->view('meeting_management/minutes_form', $data);
    }


    public function save_all($agenda_id)
    {
        // Handle task deletions
        $deleted_tasks = $this->input->post('deleted_tasks');
        if (!empty($deleted_tasks)) {
            $task_ids_to_delete = explode(',', $deleted_tasks);
            foreach ($task_ids_to_delete as $task_id) {
                $this->Task_model->delete_task($task_id);
            }
        }

        // Handle new task additions
        $new_tasks = json_decode($this->input->post('new_tasks'), true);  // Decode the JSON string
        if (!empty($new_tasks)) {
            foreach ($new_tasks as $task_data) {
                $task_data['agenda_id'] = $agenda_id;  // Ensure the task is linked to the correct agenda
                $this->Task_model->create_task($task_data);
            }
        }

        // Handle saving the minutes
        $minutes_data = [
            'minutes' => $this->input->post('minutes'),
            'updated_by' => get_staff_user_id(),
        ];
        $this->Meeting_model->save_minutes($agenda_id, $minutes_data);

        set_alert('success', 'All changes saved successfully!');
        redirect(admin_url('minutesController/convert_to_minutes/' . $agenda_id));
    }


    // Convert an agenda to minutes of meeting
    public function convert_to_minutes($agenda_id)
    {
        // Load necessary models
        $this->load->model('Meeting_model');
        $this->load->model('Task_model');

        // Fetch the agenda and ensure it exists
        $agenda = $this->Meeting_model->get_agenda($agenda_id);
        if (!$agenda) {
            show_error('Agenda not found.');
            return;
        }

        // Fetch the minutes and tasks for this agenda
        $data['agenda'] = $agenda;
        $data['minutes'] = $this->Meeting_model->get_minutes($agenda_id);
        $data['agenda_id'] = $agenda_id;
        $data['tasks'] = $this->Meeting_model->get_tasks_by_agenda($agenda_id);
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $data['attachments'] = $this->Meeting_model->get_meeting_attachments('agenda_meeting', $agenda_id);
        // Handle form submissions
        if ($this->input->post('minutes')) {
            // Save minutes
            $minutes_data = [
                'minutes' => $this->input->post('minutes')
            ];
            $this->Meeting_model->save_minutes($agenda_id, $minutes_data);

            set_alert('success', _l('meeting_minutes_created_success'));
            redirect(admin_url('meeting_management/minutesController/index/' . $agenda_id));
        }

        if ($this->input->post('task_title')) {
            // Save new task
            $task_data = [
                'agenda_id' => $agenda_id,
                'task_title' => $this->input->post('task_title'),
                'assigned_to' => $this->input->post('assigned_to'),
                'due_date' => $this->input->post('due_date')
            ];

            $this->Task_model->create_task($task_data);

            set_alert('success', _l('meeting_task_created_success'));
            redirect(admin_url('meeting_management/minutesController/index/' . $agenda_id));
        }
        $data['title'] = _l('meeting_minutes');
        $data['other_participants'] = $this->Meeting_model->get_participants($agenda_id);
        // Load the view
        $this->load->view('meeting_management/minutes_form', $data);
    }

    public function save_minutes_and_tasks($agenda_id)
    {

        // Ensure agenda_id is retrieved correctly
        if (!$agenda_id) {
            throw new Exception('Agenda ID is not valid');
        }

        // Debugging to check if agenda_id is correct
        log_message('error', 'Agenda ID during save: ' . $agenda_id);

        // Get minutes data from POST
        $minutes_data = [
            'minutes' => $this->input->post('minutes',false),
        ];
  
        // Update the minutes for this meeting
        $this->Meeting_model->update_minutes($agenda_id, $minutes_data);

        // Handle deleted tasks
        $deleted_task_ids = $this->input->post('deleted_tasks');
        if (!empty($deleted_task_ids)) {
            // Only delete if there are tasks to delete
            $this->Meeting_model->delete_tasks(explode(',', $deleted_task_ids));
        }
        // Save the participants
        $participants = $this->input->post('participants');
        $other_participants = $this->input->post('other_participants');
        $company_name = $this->input->post('company_names');
       
        if ($participants) {
            $this->Meeting_model->save_participants($agenda_id, $participants, $other_participants,$company_name);
        }
        $this->Meeting_model->save_participants($agenda_id, $participants, $other_participants,$company_name);
        // Handle new tasks if any
        $new_tasks = $this->input->post('new_tasks');  // Corrected
        if (!empty($new_tasks)) {
            foreach ($new_tasks as $task_data) {
                if (!empty($task_data['title']) && !empty($task_data['assigned_to']) && !empty($task_data['due_date'])) {
                    $task = [
                        'agenda_id' => $agenda_id,
                        'task_title' => $task_data['title'],
                        'assigned_to' => $task_data['assigned_to'],
                        'due_date' => $task_data['due_date'],
                    ];

                    $this->Task_model->create_task($task);
                }
            }
        }

        set_alert('success', 'Minutes and tasks saved successfully.');
        redirect(admin_url('meeting_management/minutesController/index/' . $agenda_id));
    }


    public function share_meeting($agenda_id)
    {
        $this->load->model('Meeting_model');
        $this->load->model('Misc_model');  // Load the correct model for notifications
        $this->load->library('email');

        // Fetch participants
        $participants = $this->Meeting_model->get_selected_participants($agenda_id);

        // Validate that participants is an array
        if (!is_array($participants)) {
            set_alert('danger', 'Participants data is not valid.');
            redirect(admin_url('meeting_management/minutesController/index/' . $agenda_id));
            return;
        }

        // Fetch meeting details to include in the email
        $meeting_details = $this->Meeting_model->get_meeting_details($agenda_id);

        foreach ($participants as $participant) {
            // Check if participant is an array before accessing its fields
            if (is_array($participant)) {
                $email = $participant['email']; // Ensure that the participant has an email field
                $name = isset($participant['firstname']) ? $participant['firstname'] . ' ' . $participant['lastname'] : '';

                // Prepare email data
                $this->email->from('no-reply@yourcrm.com', 'CRM Meeting Management');
                $this->email->to($email);
                $this->email->subject('Meeting Details');
                $this->email->message('Dear ' . $name . ', here are the meeting details: ' . json_encode($meeting_details));

                // Send email and handle errors
                if ($this->email->send()) {
                    // If email is sent, also add a notification
                    $notification_data = [
                        'description'     => 'New meeting details shared',
                        'touserid'        => $participant['staffid'] ?? $participant['userid'],  // Check if it's a staff or client ID
                        'fromcompany'     => 1,  // 1 means from the company
                        'link'            => 'meeting_management/view/' . $agenda_id,  // Link to view the meeting
                        'additional_data' => serialize(['Meeting Title: ' . $meeting_details['meeting_title']]),
                    ];
                    $this->Misc_model->add_notification($notification_data);  // Use Misc_model to add notification

                    // Trigger notification to send
                    pusher_trigger_notification([$participant['staffid'] ?? $participant['userid']]);
                } else {
                    log_message('error', 'Email to ' . $email . ' failed to send.');
                }
            } else {
                log_message('error', 'Participant data is invalid: ' . print_r($participant, true));
            }
        }

        set_alert('success', 'Meeting details have been shared successfully.');
        redirect(admin_url('meeting_management/minutesController/index/' . $agenda_id));
    }





    private function _send_meeting_email($email, $agenda, $minutes, $tasks)
    {
        $this->email->from('no-reply@yourcrm.com', 'CRM Meeting Management');
        $this->email->to($email);

        $this->email->subject(_l('meeting_minutes_subject') . ': ' . $agenda->meeting_title);

        // Prepare the email body
        $email_body = '<h3>' . _l('meeting_minutes') . '</h3>';
        $email_body .= '<p>' . $minutes->minutes . '</p>';
        $email_body .= '<h4>' . _l('meeting_tasks') . '</h4>';

        if (!empty($tasks)) {
            $email_body .= '<ul>';
            foreach ($tasks as $task) {
                $email_body .= '<li>' . $task['task_title'] . ' - ' . _l('assigned_to') . ': ' . $task['firstname'] . ' ' . $task['lastname'] . ', ' . _l('due_date') . ': ' . $task['due_date'] . '</li>';
            }
            $email_body .= '</ul>';
        } else {
            $email_body .= '<p>' . _l('no_tasks_found') . '</p>';
        }

        // Set the email body and send the email
        $this->email->message($email_body);
        $this->email->send();
    }


    // Add tasks for the participants based on meeting discussions
    public function assign_tasks($agenda_id)
    {
        if ($this->input->post()) {
            $task_data = [
                'agenda_id' => $agenda_id,
                'task_title' => $this->input->post('task_title'),
                'assigned_to' => $this->input->post('assigned_to'),
                'due_date' => $this->input->post('due_date'),
                'status' => 0, // Default to "not completed"
            ];
            $this->Meeting_model->assign_task($task_data);

            set_alert('success', _l('meeting_task_created_success'));
            redirect(admin_url('minutesController/index/' . $agenda_id));
        }

        $data['participants'] = $this->Meeting_model->get_participants($agenda_id);
        $data['title'] = _l('assign_meeting_task');
        $this->load->view('meeting_management/assign_task_form', $data);
    }

    public function delete_task()
    {
        $task_id = $this->input->post('task_id');

        if (!empty($task_id)) {
            $this->load->model('Task_model');

            $deleted = $this->Task_model->delete_task($task_id);

            if ($deleted) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }
    public function export_meeting_to_pdf()
    {
        $this->load->library('pdf');  // Load PDF library (e.g., dompdf)

        $content = $this->input->post('content');
        $pdf_content = '<html><body>' . $content . '</body></html>';

        // Set the PDF settings
        $this->pdf->load_html($pdf_content);
        $this->pdf->render();
        $this->pdf->stream('meeting_details.pdf', array('Attachment' => 1)); // Force download PDF
    }
    public function view_meeting($agenda_id)
    {
        // Fetch meeting details
        $data['meeting'] = $this->Meeting_model->get_meeting_details($agenda_id);

        // Fetch participants
        $data['participants'] = $this->Meeting_model->get_selected_participants($agenda_id);

        // Fetch tasks
        $data['tasks'] = $this->Meeting_model->get_tasks_by_agenda($agenda_id);

        $data['attachments'] = $this->Meeting_model->get_meeting_attachments('agenda_meeting', $agenda_id);

        $data['other_participants'] = $this->Meeting_model->get_participants($agenda_id);
        
        // Load the view
        $this->load->view('meeting_management/view_meeting', $data);
    }

    public function delete_attachment($id)
    {
        $this->Meeting_model->delete_meeting_attachment($id);
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function file_meeting_preview($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->Meeting_model->get_meeting_attachments_with_id($id);
        // echo '<pre>';
        // print_r($data);
        // die;
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('meeting_management/_file', $data);
    }

    public function update_minutes_of_meeting()
    {
        $data = $this->input->post();
        $this->Meeting_model->update_minutes_of_meeting($data);
        echo json_encode(['success' => true]);
        die();
    }
}
