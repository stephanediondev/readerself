<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends CI_Controller {
	public function error() {
		if($this->config->item('salt_password')) {
			redirect(base_url());
		}

		$data = array();
		$content = $this->load->view('configuration_error', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
}
