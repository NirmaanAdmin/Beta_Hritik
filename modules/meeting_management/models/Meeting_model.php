<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meeting_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get all agendas with the project name
    public function get_all_agendas()
    {
        $this->db->select('tblagendas.*, tblprojects.name as project_name');
        $this->db->from('tblagendas');
        $this->db->join('tblprojects', 'tblprojects.id = tblagendas.project_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Create a new agenda
    public function create_agenda($data)
{
    // Insert into the agendas table
    $this->db->insert(db_prefix() . 'agendas', $data);
    $agenda_id = $this->db->insert_id();

    // Insert into the meeting_management table as well
    $meeting_data = [
        'meeting_title' => $data['meeting_title'],
        'agenda' => $data['agenda'],  // Add other fields that are relevant for the meeting_management table
        'meeting_date' => $data['meeting_date'],
        'created_by' => $data['created_by'],
        'project_id' => $data['project_id'],
    ];
    $this->db->insert(db_prefix() . 'meeting_management', $meeting_data);

    return $agenda_id;
}


    // Update an existing agenda
    public function update_agenda($id, $data)
    {
        // Update the agenda in the 'tblagendas' table
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'agendas', $data);
        $affected_rows = $this->db->affected_rows();
    
        if ($affected_rows > 0) {
            // Also update the 'meeting_management' table
            $meeting_data = [
                'meeting_title' => $data['meeting_title'],
                'agenda' => $data['agenda'],
                'meeting_date' => $data['meeting_date'],
                'updated_by' => $data['updated_by']  // Ensure you're tracking who updated the record
            ];
            $this->db->where('id', $id);  // Use the same ID as the agenda
            $this->db->update(db_prefix() . 'meeting_management', $meeting_data);
        }
    
        return $affected_rows;
    }
    public function delete_agenda($id)
    {
        // Delete the agenda from the 'tblagendas' table
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'agendas');
        $affected_rows = $this->db->affected_rows();
    
        if ($affected_rows > 0) {
            // Also delete the corresponding entry from the 'meeting_management' table
            $this->db->where('id', $id);  // Use the same ID as the agenda
            $this->db->delete(db_prefix() . 'meeting_management');
        }
    
        return $affected_rows;
    }
        

    // Get a single agenda by ID
    public function get_agenda($id)
    {
        $this->db->select('tblagendas.*, tblprojects.name as project_name');
        $this->db->from('tblagendas');
        $this->db->join('tblprojects', 'tblprojects.id = tblagendas.project_id', 'left');
        $this->db->where('tblagendas.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get existing minutes for the agenda
    public function get_minutes($agenda_id)
    {
        $this->db->select('meeting_title, minutes, agenda');
        $this->db->where('id', $agenda_id);
        $query = $this->db->get(db_prefix() . 'meeting_management');  // Use the correct table name here
        return $query->row();
    }

    // Save minutes for the agenda
    public function save_minutes($agenda_id, $minutes_data)
    {
        // Update the minutes in the database for the given agenda
        $this->db->where('id', $agenda_id);
        return $this->db->update(db_prefix() . 'meeting_management', $minutes_data);
    }

    // Fetch participants for the agenda
    // Restored the original function for convert_to_minutes
    public function get_selected_participants($agenda_id)
    {
        $this->db->select('participant_id');
        $this->db->from(db_prefix() . 'meeting_participants');
        $this->db->where('meeting_id', $agenda_id);
        $query = $this->db->get();
        
        return array_column($query->result_array(), 'participant_id');  // Return array of participant IDs
    }

    // Fetch detailed participants for viewing in view_meeting
    public function get_detailed_participants($agenda_id)
    {
        // Fetch staff participants
        $this->db->select('tblstaff.staffid as participant_id, tblstaff.firstname, tblstaff.lastname, tblstaff.email');
        $this->db->from(db_prefix() . 'meeting_participants');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'meeting_participants.participant_id', 'left');
        $this->db->where('meeting_id', $agenda_id);
        $staff_query = $this->db->get_compiled_select();

        // Fetch client participants
        $this->db->select('tblclients.userid as participant_id, tblclients.company as firstname, "" as lastname, tblcontacts.email');
        $this->db->from(db_prefix() . 'meeting_participants');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'meeting_participants.participant_id', 'left');
        $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.userid = ' . db_prefix() . 'clients.userid', 'left');
        $this->db->where('meeting_id', $agenda_id);
        $client_query = $this->db->get_compiled_select();

        // Combine both queries using UNION
        $query = $this->db->query($staff_query . ' UNION ' . $client_query);
        
        return $query->result_array();
    }

    // Assign tasks to participants
    public function assign_task($task_data)
    {
        $this->db->insert(db_prefix() . 'meeting_tasks', $task_data);
        return $this->db->insert_id();
    }

    // Save a digital signature for the participant
    public function save_signature($agenda_id, $signature_path)
    {
        $this->db->where('id', $agenda_id);
        $this->db->update(db_prefix() . 'meeting_management', ['signature_path' => $signature_path]);
        return $this->db->affected_rows();
    }

    // Create a task for the meeting agenda
    public function create_task($task_data)
    {
        $this->db->insert(db_prefix() . 'meeting_tasks', $task_data);
        return $this->db->insert_id();
    }

    // Fetch tasks for a given agenda
    public function get_tasks_by_agenda($agenda_id)
    {
        $this->db->select('tblmeeting_tasks.*, tblstaff.firstname, tblstaff.lastname');
        $this->db->from('tblmeeting_tasks');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblmeeting_tasks.assigned_to');
        $this->db->where('agenda_id', $agenda_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Delete tasks by task IDs
    public function delete_tasks($task_ids)
    {
        if (!empty($task_ids)) {
            $this->db->where_in('id', $task_ids);
            return $this->db->delete(db_prefix() . 'meeting_tasks');
        }
    }

    // Update minutes for a given agenda
    public function update_minutes($agenda_id, $minutes_data)
    {
        $this->db->where('id', $agenda_id);
        return $this->db->update(db_prefix() . 'meeting_management', $minutes_data);
    }

    // Save participants for a given agenda
    public function save_participants($agenda_id, $participants)
    {
        // First, ensure the meeting ID exists in the database
        $this->db->where('id', $agenda_id);
        $meeting_exists = $this->db->get(db_prefix() . 'meeting_management')->row();
    
        if (!$meeting_exists) {
            throw new Exception('Meeting ID does not exist');
        }
    
        // First, delete all existing participants for the agenda
        $this->db->where('meeting_id', $agenda_id);
        $this->db->delete(db_prefix() . 'meeting_participants');
    
        // Insert new participants
        if (!empty($participants)) {
            foreach ($participants as $participant_id) {
                $data = [
                    'meeting_id' => $agenda_id,
                    'participant_id' => $participant_id, // Could be either staff or client ID
                ];
    
                // Insert participants
                $this->db->insert(db_prefix() . 'meeting_participants', $data);
            }
        }
    }
    

    // Get all tasks for an agenda (used in different contexts)
    public function get_all_tasks($agenda_id)
    {
        $this->db->select('tblmeeting_tasks.*, tblstaff.firstname, tblstaff.lastname');
        $this->db->from(db_prefix() . 'meeting_tasks');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'meeting_tasks.assigned_to', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $query = $this->db->get();
        return $query->result_array();  // Returns an array of tasks
    }

        // Get meeting details for a given agenda
        public function get_meeting_details($agenda_id)
    {
        $this->db->select('id as meeting_id, meeting_title, agenda, meeting_date'); // Make sure 'id' is included as 'meeting_id'
        $this->db->from(db_prefix() . 'meeting_management'); // Replace with your actual table name
        $this->db->where('id', $agenda_id); // Assuming 'id' is the primary key of the meeting table
        $query = $this->db->get();
        
        return $query->row_array(); // Return the meeting details as an associative array
    }

    public function get_client_meetings($client_id)
    {
        $this->db->select('meeting_management.id, meeting_management.meeting_title, meeting_management.meeting_date, meeting_management.agenda');
        $this->db->from(db_prefix() . 'meeting_management as meeting_management');
        $this->db->join(db_prefix() . 'meeting_participants as participants', 'participants.meeting_id = meeting_management.id');
        $this->db->where('participants.participant_id', $client_id);  // Make sure this references the correct participant_id
       
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    
    
    
    

    public function get_meeting_details_for_client($meeting_id, $client_id)
    {
        $this->db->select('m.meeting_title, m.agenda, m.meeting_date, m.minutes');
        $this->db->from(db_prefix() . 'meeting_management as m');
        $this->db->join(db_prefix() . 'meeting_participants as p', 'p.meeting_id = m.id', 'left');
        $this->db->where('m.id', $meeting_id);
        $this->db->where('p.participant_id', $client_id);  // Ensure the client is a participant
        
        return $this->db->get()->row_array();
    }
    
    
    
    

    public function get_meeting_notes($agenda_id)
    {
        // Select the 'minutes' field that holds the meeting notes
        $this->db->select('minutes');  
        $this->db->from(db_prefix() . 'meeting_management');  // Ensure correct table name
        $this->db->where('id', $agenda_id);  // Assuming 'id' is the primary key
        $query = $this->db->get();
        
        // Return the 'minutes' field if found, otherwise return an empty string
        if ($query->num_rows() > 0) {
            $minutes = $query->row()->minutes;
            return !empty($minutes) ? $minutes : 'No meeting notes available.';
        }
        return 'No meeting found with the provided ID.';
    }
    




}
