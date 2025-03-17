<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        // Load necessary models
        $this->load->model('Meeting_model');
        $this->load->model('Clients_model');  // Assuming you're fetching some client data
    }

    public function meeting_notes()
    {
        // Get client ID
        $client_id = get_client_user_id();  // This gets the logged-in client's ID
    
        // Fetch meeting details for this client
        $data['meetings'] = $this->Meeting_model->get_client_meetings($client_id);  // Fetch meetings using the method above
       
    
        // Load the client view
        $data['title'] = _l('meeting_minutes');
        $this->data($data);
        $this->view('meeting_management/client_meeting_notes');  // Load the view to display meeting notes
        $this->layout();
    }
    

    public function view_meeting($meeting_id)
    {
        $client_id = get_client_user_id();
        $data['meeting'] = $this->Meeting_model->get_meeting_details_for_client($meeting_id, $client_id);
       
        if (!$data['meeting']) {
            set_alert('danger', _l('meeting_not_found'));
            redirect(site_url('meeting_management/clients/meeting_notes'));
        }
    
        $data['title'] = _l('meeting_minutes');
        $this->data($data);
        $this->view('meeting_management/view_meeting_client');
        $this->layout();
    }
    
    
    

}
