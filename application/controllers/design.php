<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Design extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		$data = array();
		$content = $this->load->view('design_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
}
