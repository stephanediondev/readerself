<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_tags_tag_title'] = array('tag.tag_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'tag.mbr_id = \''.$this->member->mbr_id.'\'';
		$columns = array();
		$columns[] = 'tag.tag_title';
		$columns[] = 'subscriptions';
		$col = $this->reader_library->build_columns($this->router->class.'_tags', $columns, 'tag.tag_title', 'ASC');
		$results = $this->reader_model->get_tags_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_tags');
		$data = array();
		$data['columns'] = $col;
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['tags'] = $this->reader_model->get_tags_rows($flt, $build_pagination['limit'], $build_pagination['start'], $this->router->class.'_tags');

		$content = $this->load->view('tags_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('tag_title', 'Title', 'required');
		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('tags_create', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->set('mbr_id', $this->member->mbr_id);
			$this->db->set('tag_title', $this->input->post('tag_title'));
			$this->db->set('tag_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('tags');
			$tag_id = $this->db->insert_id();

			//$this->read($tag_id);
			redirect(base_url().'tags');
		}
	}

	public function update($tag_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['tag'] = $this->reader_model->get_tag_row($tag_id);
		if($data['tag']) {
			$this->form_validation->set_rules('tag_title', 'lang:tag_title', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('tags_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('tag_title', $this->input->post('tag_title'));
				$this->db->where('tag_id', $tag_id);
				$this->db->update('tags');

				//$this->read($tag_id);
				redirect(base_url().'tags');
			}
		} else {
			$this->index();
		}
	}

	public function delete($tag_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['tag'] = $this->reader_model->get_tag_row($tag_id);
		if($data['tag']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('tags_delete', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('tag_id', '');
				$this->db->where('tag_id', $tag_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->update('subscriptions');

				$this->db->where('tag_id', $tag_id);
				$this->db->delete('tags');

				redirect(base_url().'tags');
			}
		} else {
			$this->index();
		}
	}
}
