<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pinboard extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index() {
		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$content = array();

		$this->readerself_library->set_content($content);
	}
	public function configure() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['token'] = $this->readerself_model->get_token('pinboard', $this->member->mbr_id, false);

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('api_token', 'API Token');

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('pinboard_configure', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			if($this->input->post('api_token') == '') {
				$this->db->query('DELETE FROM '.$this->db->dbprefix('tokens').' WHERE tok_type = ? AND mbr_id = ?', array('pinboard', $this->member->mbr_id));
			} else {
				$this->readerself_model->set_token('pinboard', $this->member->mbr_id, $this->input->post('api_token'), false);
			}

			redirect(base_url().'pinboard/configure');
		}
	}
}
