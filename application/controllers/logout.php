<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout  extends CI_Controller {
	public function index() {
		$this->reader_model->logout();

		redirect(base_url());
	}
}
