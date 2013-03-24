<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$query = $this->db->query('SELECT tag.*, (SELECT COUNT(DISTINCT(count_sub_tag.sub_id)) FROM '.$this->db->dbprefix('subscriptions_tags').' AS count_sub_tag WHERE count_sub_tag.tag_id = tag.tag_id) AS subscriptions FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? GROUP BY tag.tag_id ORDER BY tag.tag_title ASC', array($this->member->mbr_id));

		$data = array();
		$data['tags'] = $query->result();
		$content = $this->load->view('tags_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function add() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->load->library(array('form_validation'));

			$this->form_validation->set_rules('tag_title', 'Title', 'required');

			if($this->form_validation->run() == FALSE) {
			} else {
				$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_title = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $this->input->post('tag_title')));
				if($query->num_rows() == 0) {
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('tag_title', $this->input->post('tag_title'));
					$this->db->set('tag_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('tags');
					$tag_id = $this->db->insert_id();

					$data['alert'] = array('type'=>'success', 'message'=>'Added');
				} else {
					$data['alert'] = array('type'=>'error', 'message'=>'Already exists');
				}
			}
			$content['modal'] = $this->load->view('tags_add', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function delete($tag_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $tag_id));
			if($query->num_rows() > 0) {
				$data['tag'] = $query->row();

				$content['modal'] = $this->load->view('tags_delete', $data, TRUE);
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function delete_confirm($tag_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $tag_id));
			if($query->num_rows() > 0) {
				$data['tag'] = $query->row();

				$this->db->where('tag_id', $tag_id);
				$this->db->delete('tags');

				$this->db->where('tag_id', $tag_id);
				$this->db->delete('subscriptions_tags');

				$content['modal'] = $this->load->view('tags_delete_confirm', $data, TRUE);
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}
