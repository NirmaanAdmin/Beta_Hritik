<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPPATH . 'libraries/pdf/App_pdf.php';

/**
 * Vendor allocation report pdf
 */
class Vendor_allocation_report_pdf extends App_pdf {
	protected $vendor_allocation_report;
	public $font_size = '';
	public $size = 9;

	/**
	 * get font size
	 * @return
	 */
	public function get_font_size() {
		return $this->font_size;
	}

	/**
	 * set font size
	 * @param
	 */
	public function set_font_size($size) {
		$this->font_size = 8;

		return $this;
	}

	/**
	 * construct
	 * @param
	 */
	public function __construct($vendor_allocation_report) {

		$vendor_allocation_report = hooks()->apply_filters('request_html_pdf_data', $vendor_allocation_report);
		$GLOBALS['vendor_allocation_report_pdf'] = $vendor_allocation_report;

		parent::__construct();

		$this->vendor_allocation_report = $vendor_allocation_report;

		$this->SetTitle('vendor_allocation_report');

		# Don't remove these lines - important for the PDF layout
		$this->vendor_allocation_report = $this->fix_editor_html($this->vendor_allocation_report);
	}

	/**
	 * prepare
	 * @return
	 */
	public function prepare() {
		$this->set_view_vars('vendor_allocation_report', $this->vendor_allocation_report);

		return $this->build();
	}

	/**
	 * type
	 * @return
	 */
	protected function type() {
		return 'vendor_allocation_report';
	}

	/**
	 * file path
	 * @return
	 */
	protected function file_path() {
		$customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
		$actualPath = APP_MODULES_PATH . '/warehouse/views/report/vendor_allocation_reportpdf.php';

		if (file_exists($customPath)) {
			$actualPath = $customPath;
		}

		return $actualPath;
	}
}