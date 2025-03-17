<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AttendanceController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Load language file and models
        $this->lang->load('meeting_management', 'english');
        $this->load->model('Meeting_model');
    }

    // Display attendance form with a signature pad
    public function sign_attendance($agenda_id)
    {
        $data['agenda'] = $this->Meeting_model->get_agenda($agenda_id);
        $data['title'] = _l('meeting_attendance');
        $this->load->view('meeting_management/sign_attendance', $data);
    }

    // Save participant signature
    public function save_signature()
    {
        $agenda_id = $this->input->post('agenda_id');
        $signatureData = $this->input->post('signature');
        
        if ($signatureData) {
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = base64_decode($signatureData);
            $file_path = 'uploads/signatures/meeting_' . $agenda_id . '_signature.png';
            file_put_contents($file_path, $signatureData);
            $this->Meeting_model->save_signature($agenda_id, $file_path);
            echo json_encode(['status' => 'success', 'message' => _l('meeting_signature_saved_success')]);
        } else {
            echo json_encode(['status' => 'error', 'message' => _l('meeting_error')]);
        }
    }
}
