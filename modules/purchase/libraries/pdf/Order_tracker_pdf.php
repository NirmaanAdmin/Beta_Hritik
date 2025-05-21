<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Order_tracker_pdf extends App_pdf
{
    protected $order_tracker;

    public function __construct($order_tracker)
    {
        $order_tracker                = hooks()->apply_filters('request_html_pdf_data', $order_tracker);
        $GLOBALS['Order_tracker_pdf'] = $order_tracker;
        parent::__construct();
        $this->order_tracker = $order_tracker;

        $this->SetTitle(_l('order_tracker'));
        # Don't remove these lines - important for the PDF layout
        $this->order_tracker = $this->fix_editor_html($this->order_tracker);
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
        $this->set_view_vars('order_tracker', $this->order_tracker);

        return $this->build();
    }

    protected function type()
    {
        return 'order_tracker';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/purchase/views/order_tracker/order_tracker_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}