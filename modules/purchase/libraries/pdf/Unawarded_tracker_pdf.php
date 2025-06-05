<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Unawarded_tracker_pdf extends App_pdf
{
    protected $unawarded_tracker;

    public function __construct($unawarded_tracker)
    {
        $unawarded_tracker                = hooks()->apply_filters('request_html_pdf_data', $unawarded_tracker);
        $GLOBALS['Unawarded_tracker_pdf'] = $unawarded_tracker;
        parent::__construct();
        $this->unawarded_tracker = $unawarded_tracker;

        $this->SetTitle(_l('unawarded_tracker'));
        # Don't remove these lines - important for the PDF layout
        $this->unawarded_tracker = $this->fix_editor_html($this->unawarded_tracker);
    }

    // Override the Footer method from TCPDF or FPDI
    public function Footer()
    {
        // Trigger the custom hook for the footer content
        hooks()->do_action('pdf_footer', ['pdf_instance' => $this, 'type' => $this->type]);
       
        $this->SetY(-20); // 15mm from the bottom
        $this->SetX(-15); // 15mm from the bottom
        $this->SetFont($this->get_font_name(), 'I', 8);
        $this->SetTextColor(142, 142, 142);
        $this->Cell(0, 15, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }


    public function prepare()
    {
        $this->set_view_vars('unawarded_tracker', $this->unawarded_tracker);

        return $this->build();
    }

    protected function type()
    {
        return 'unawarded_tracker';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/purchase/views/unawarded_tracker/unawarded_tracker_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}