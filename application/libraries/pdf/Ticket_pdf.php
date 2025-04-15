<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Ticket_pdf extends App_pdf
{
    protected $ticket;
    protected $subject;

    public function __construct($ticket)
    {
        $GLOBALS['ticket_pdf'] = $ticket;

        parent::__construct();

        if (!class_exists('Tickets_model', false)) {
            $this->ci->load->model('tickets_model');
        }

        $this->ticket = $ticket;
        $this->subject = $this->ticket->subject;
        $this->ticket_replies  = $this->ci->tickets_model->get_ticket_replies($this->ticket->ticketid);

        $this->SetTitle($this->subject);
    }

    public function prepare()
    {
        $this->set_view_vars([
            'subject' => $this->subject,
            'ticket' => $this->ticket,
            'ticket_replies' => $this->ticket_replies,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'ticket';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_ticketpdf.php';
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/ticketpdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}
