<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Dms_share_pdf extends App_pdf
{
    protected $transmittal;

    public function __construct($transmittal)
    {
        // Apply any filters to the provided data
        $transmittal = hooks()->apply_filters('request_html_pdf_data', $transmittal);
        $GLOBALS['dms_share_pdf'] = $transmittal;
        parent::__construct();
        
        // Assign the transmittal data for use within the class
        $this->transmittal = $transmittal;
        
        // Set the PDF title
        $this->SetTitle(_l('Transmittal'));
        // Fix any editor-related HTML if needed
        $this->transmittal = $this->fix_editor_html($this->transmittal);
    }

    public function prepare()
    {
        // Pass the transmittal data to the view variables
        $this->set_view_vars('transmittal', $this->transmittal);

        // Build and return the PDF
        return $this->build();
    }

    protected function type()
    {
        // Return an identifier for this PDF type
        return 'transmittal';
    }

    protected function file_path()
    {
        // Try to use a custom view if available
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/dms_share_pdf.php';
        // Fallback default view file
        $defaultPath = APP_MODULES_PATH . '/drawing_management/views/file_managements/dms_share_pdf.php';

        if (file_exists($customPath)) {
            return $customPath;
        }
        return $defaultPath;
    }
}
