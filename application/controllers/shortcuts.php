<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shortcuts extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$content = $this->load->view('shortcuts', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
}
