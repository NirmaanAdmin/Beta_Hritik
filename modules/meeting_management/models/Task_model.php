<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_model extends CI_Model
{
    public function get_all_tasks($agenda_id)
    {
        $this->db->select('tblmeeting_tasks.*, tblstaff.firstname, tblstaff.lastname');
        $this->db->from(db_prefix() . 'meeting_tasks');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'meeting_tasks.assigned_to', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $query = $this->db->get();
        
        return $query->result_array();
    }
    

    public function create_task($task_data)
    {
        $this->db->insert(db_prefix() . 'meeting_tasks', $task_data);
    }
    public function delete_task($task_id)
    {
        $this->db->where('id', $task_id);
        return $this->db->delete(db_prefix() . 'meeting_tasks');
    }
    
public function update_task($task_id, $task_data)
    {
        $this->db->where('id', $task_id);
        $this->db->update(db_prefix() . 'meeting_tasks', $task_data);
    }

    public function get_upcoming_tasks($today)
    {
        $this->db->where('due_date >=', $today);
        $this->db->where('reminder_sent', 0); // Only fetch tasks that have not been reminded
        return $this->db->get(db_prefix() . 'meeting_tasks')->result();
    }

    public function mark_reminder_sent($task_id)
    {
        $this->db->where('id', $task_id);
        $this->db->update(db_prefix() . 'meeting_tasks', ['reminder_sent' => 1]);
    }
    public function get_last_task($agenda_id)
{
    $this->db->select('tblmeeting_tasks.*, tblstaff.firstname, tblstaff.lastname');
    $this->db->from('tblmeeting_tasks');
    $this->db->join('tblstaff', 'tblstaff.staffid = tblmeeting_tasks.assigned_to');
    $this->db->where('agenda_id', $agenda_id);
    $this->db->order_by('tblmeeting_tasks.id', 'DESC');
    $this->db->limit(1);

    $query = $this->db->get();
    return $query->row_array();
}

}
