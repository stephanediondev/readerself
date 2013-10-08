<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extension extends CI_Controller {
	public function background() {
		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$content = array();

		if($this->session->userdata('mbr_id')) {
			$content['logged'] = true;
			$content['unread'] = $this->readerself_model->count_unread('all');
		} else {
			$content['logged'] = false;
		}
		$this->readerself_library->set_content($content);
	}
}
