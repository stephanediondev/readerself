<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$query = $this->db->query('SELECT fed.*, sub.sub_id, sub.tag_id, tag.tag_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('tags').' AS tag ON tag.tag_id = sub.tag_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id));

		$data = array();
		$data['subscriptions'] = $query->result();

		$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? GROUP BY tag.tag_id ORDER BY tag.tag_title ASC', array($this->member->mbr_id));

		$data['tags'] = $query->result();

		$content = $this->load->view('subscriptions_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function delete($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				$data['sub'] = $query->row();

				$content['modal'] = $this->load->view('subscriptions_delete', $data, TRUE);
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function delete_confirm($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				$data['sub'] = $query->row();

				$this->db->where('sub_id', $sub_id);
				$this->db->delete('subscriptions');

				$content['modal'] = $this->load->view('subscriptions_delete_confirm', $data, TRUE);
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function tag($sub_id, $tag_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$content = array();
		$content['sub_id'] = $sub_id;

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				if($tag_id == 0) {
					$this->db->set('tag_id', '');
					$this->db->where('sub_id', $sub_id);
					$this->db->update('subscriptions');
				} else {
					$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $tag_id));
					if($query->num_rows() > 0) {
						$this->db->set('tag_id', $tag_id);
						$this->db->where('sub_id', $sub_id);
						$this->db->update('subscriptions');
					}
				}
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}
