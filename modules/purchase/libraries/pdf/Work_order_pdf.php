<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Work_order_pdf extends App_pdf
{
    protected $wo_order;
    protected $footer_text;

    public function __construct($wo_order, $footer_text = '')
    {
        $wo_order                = hooks()->apply_filters('request_html_pdf_data', $wo_order);
        $GLOBALS['wo_order_pdf'] = $wo_order;
        parent::__construct();
        
        $this->footer_text = $footer_text;
        $this->wo_order = $wo_order;
        
        $this->SetTitle(_l('wo_order'));
        # Don't remove these lines - important for the PDF layout
        $this->wo_order = $this->fix_editor_html($this->wo_order);
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

        if($this->footer_text !== '') {
            // Set default footer position and font (if additional styling needed)
            $this->SetX(15); // 15mm from the bottom
            $this->SetY(-15); // 15mm from the bottom
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, $this->footer_text, 0, 0, 'L');
        }
    }


    public function prepare()
    {
        $this->set_view_vars('wo_order', $this->wo_order);

        return $this->build();
    }

    protected function type()
    {
        return 'wo_order';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/purchase/views/work_order/wo_orderpdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}