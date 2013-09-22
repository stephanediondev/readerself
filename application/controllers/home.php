<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));

		$data = array();
		$data['folders'] = $query->result();
		$content = $this->load->view('home_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function timezone() {
		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->session->set_userdata('timezone', $this->input->post('timezone'));
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function geolocation() {
		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->session->set_userdata('latitude', floatval($this->input->post('latitude')));
			$this->session->set_userdata('longitude', floatval($this->input->post('longitude')));
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function shortcuts() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$content['modal'] = $this->load->view('home_shortcuts', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}

	public function error($type) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			if($type == 'geo1') {
				$data['error'] = '<p>Geolocation: permission denied</p>';
			} else if($type == 'geo2') {
				$data['error'] = '<p>Geolocation: position unavailable</p>';
			} else if($type == 'geo3') {
				$data['error'] = '<p>Geolocation: timeout</p>';
			} else if($type == 'geo0') {
				$data['error'] = '<p>Geolocation: unknown error</p>';
			} else {
				$data['error'] = '<p>Unknown error</p>';
			}

			$content['modal'] = $this->load->view('home_error', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}
