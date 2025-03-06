<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Invoice_pdf extends App_pdf
{
    protected $invoice;

    private $invoice_number;
    protected $footer_text;
    protected $project_name;

    public function __construct($invoice, $tag = '',  $footer_text = '')
    {
        $this->load_language($invoice->clientid);
        $invoice                = hooks()->apply_filters('invoice_html_pdf_data', $invoice);
        $GLOBALS['invoice_pdf'] = $invoice;

        parent::__construct();

        $companyName = get_option('invoice_company_name');
        $companyNameFooter = implode('', array_map(fn($word) => mb_substr($word, 0, 1), array_filter(explode(' ', $companyName))));

        $this->footer_text = $companyNameFooter.'-'.$invoice->title;
        $this->project_name = $invoice->project_data->name;
        if (!class_exists('Invoices_model', false)) {
            $this->ci->load->model('invoices_model');
        }

        $this->tag            = $tag;
        $this->invoice        = $invoice;
        $this->invoice_number = format_invoice_number($this->invoice->id);
        $this->basic_invoice  = $this->ci->invoices_model->get_annexure_invoice_details($this->invoice->id);

        $this->SetTitle($this->invoice_number);
    }

    public function Header() {
        // Skip header on the first page
        if ($this->PageNo() == 1) {
            return;
        }

        // Get company name
        $companyName = get_option('invoice_company_name');

        // Move to the absolute top of the page
        $this->SetY(5);  // Adjust if needed

        // Set project name (Bold, Larger font)
        $this->SetFont('helvetica', 'B', 8);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 5, $this->project_name, 0, 1, 'R');

        // Set company name (Regular, Smaller font)
        $this->SetFont('helvetica', '', 6);
        $this->SetTextColor(100, 100, 100); // Light Gray
        $this->Cell(0, 5, $companyName, 0, 1, 'R');
    }


    public function Footer()
    {
        // Trigger the custom hook for the footer content
        hooks()->do_action('pdf_footer', ['pdf_instance' => $this, 'type' => $this->type]);

        // Set footer position
        $this->SetY(-15); // Position 15mm from bottom
        $this->SetFont($this->get_font_name(), 'I', 8);
        $this->SetTextColor(142, 142, 142);

        // Left side text
        if (!empty($this->footer_text)) {
            $this->SetX(10); // Position towards left side
            $this->Cell(0, 10, $this->footer_text, 0, 0, 'L'); // Align left
        }

        // Right side page number
        $this->SetX(-30); // Move to right
        $this->Cell(0, 10, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R'); // Align right
    }


    public function prepare()
    {
        $this->with_number_to_word($this->invoice->clientid);

        $this->set_view_vars([
            'status'         => $this->invoice->status,
            'invoice_number' => $this->invoice_number,
            'payment_modes'  => $this->get_payment_modes(),
            'invoice'        => $this->invoice,
            'basic_invoice'  => $this->basic_invoice,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'invoice';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_invoicepdf.php';
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/invoicepdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }

    private function get_payment_modes()
    {
        $this->ci->load->model('payment_modes_model');
        $payment_modes = $this->ci->payment_modes_model->get();

        // In case user want to include {invoice_number} or {client_id} in PDF offline mode description
        foreach ($payment_modes as $key => $mode) {
            if (isset($mode['description'])) {
                $payment_modes[$key]['description'] = str_replace('{invoice_number}', $this->invoice_number, $mode['description']);
                $payment_modes[$key]['description'] = str_replace('{client_id}', $this->invoice->clientid, $mode['description']);
            }
        }

        return $payment_modes;
    }
}
