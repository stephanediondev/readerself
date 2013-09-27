<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error404 extends CI_Controller {
	public function index() {
		$this->output->set_status_header(404);

		$data = array();
		$content = $this->load->view('error404_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
}
