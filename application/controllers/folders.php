<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Folders extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_folders_flr_title'] = array('flr.flr_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'flr.mbr_id = \''.$this->member->mbr_id.'\'';
		$columns = array();
		$columns[] = 'flr.flr_title';
		$columns[] = 'subscriptions';
		$col = $this->reader_library->build_columns($this->router->class.'_folders', $columns, 'flr.flr_title', 'ASC');
		$results = $this->reader_model->get_folders_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_folders');
		$data = array();
		$data['columns'] = $col;
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['folders'] = $this->reader_model->get_folders_rows($flt, $build_pagination['limit'], $build_pagination['start'], $this->router->class.'_folders');

		$content = $this->load->view('folders_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('flr_title', 'Title', 'required');
		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('folders_create', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->set('mbr_id', $this->member->mbr_id);
			$this->db->set('flr_title', $this->input->post('flr_title'));
			$this->db->set('flr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('folders');
			$flr_id = $this->db->insert_id();

			//$this->read($flr_id);
			redirect(base_url().'folders');
		}
	}

	public function update($flr_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['folder'] = $this->reader_model->get_flr_row($flr_id);
		if($data['folder']) {
			$this->form_validation->set_rules('flr_title', 'lang:flr_title', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('flr_title', $this->input->post('flr_title'));
				$this->db->where('flr_id', $flr_id);
				$this->db->update('folders');

				//$this->read($flr_id);
				redirect(base_url().'folders');
			}
		} else {
			$this->index();
		}
	}

	public function delete($flr_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['folder'] = $this->reader_model->get_flr_row($flr_id);
		if($data['folder']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_delete', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('flr_id', '');
				$this->db->where('flr_id', $flr_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->update('subscriptions');

				$this->db->where('flr_id', $flr_id);
				$this->db->delete('folders');

				redirect(base_url().'folders');
			}
		} else {
			$this->index();
		}
	}
}
