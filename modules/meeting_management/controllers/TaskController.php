<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TaskController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('Task_model');
        $this->load->model('Staff_model');
        $this->load->model('Meeting_model');  // Load the Meeting_model
    }

    // View all tasks assigned during the meeting
    public function index($agenda_id = null) // Make $agenda_id optional
    {
        if ($agenda_id === null) {
            show_error('Agenda ID is required to view tasks.');
            return;
        }

        // Fetch tasks related to the specific agenda
        $data['tasks'] = $this->Task_model->get_all_tasks($agenda_id);
        $data['title'] = _l('meeting_tasks');
        $data['agenda_id'] = $agenda_id; // Pass agenda_id to view for future use

        // Load the tasks list view
        $this->load->view('meeting_management/tasks_list', $data);
    }

    // Create a new task and assign it
   // Create a new task and assign it
   public function create($agenda_id)
   {
       if ($this->input->post()) {
           $task_data = [
               'agenda_id' => $agenda_id,
               'task_title' => $this->input->post('task_title'),
               'assigned_to' => $this->input->post('assigned_to'),
               'due_date' => $this->input->post('due_date'),
           ];
   
           $this->Task_model->create_task($task_data);
           
           // Fetch the newly created task to return with staff names
           $new_task = $this->Task_model->get_last_task($agenda_id); // Make sure you have this method
           
           // Return JSON response
           echo json_encode(['success' => true, 'task' => $new_task]);
       }
   }
   
    
    
    

    // Custom validation callback for due date
    public function validate_due_date($due_date)
    {
        $current_date = date('Y-m-d');
        if (strtotime($due_date) < strtotime($current_date)) {
            $this->form_validation->set_message('validate_due_date', _l('invalid_due_date'));
            return false;
        }
        return true;
    }

    // Send reminders for the tasks
    public function send_task_reminders()
    {
        $today = date('Y-m-d');
        $tasks = $this->Task_model->get_upcoming_tasks($today);

        foreach ($tasks as $task) {
            if ((strtotime($task->due_date) - strtotime($today)) <= 86400) { // Reminder if due date is in 1 day
                $this->_send_reminder_email($task);
                $this->Task_model->mark_reminder_sent($task->id);
            }
        }
    }

    private function _send_reminder_email($task)
    {
        $this->load->library('email');
        $this->email->from('no-reply@yourcrm.com', 'CRM Meeting Management');
        $this->email->to($task->assigned_to);

        $this->email->subject(_l('meeting_task_reminder'));
        $this->email->message(sprintf(_l('meeting_task_reminder_message'), $task->task_title, $task->due_date));
        $this->email->send();
    }
}
