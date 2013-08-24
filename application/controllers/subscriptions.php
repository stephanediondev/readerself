<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_subscriptions_fed_title'] = array('fed.fed_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'sub.mbr_id = \''.$this->member->mbr_id.'\'';
		$flt[] = 'fed.fed_id IS NOT NULL';
		$columns = array();
		$columns[] = 'fed.fed_title';
		$columns[] = 'fed.fed_description';
		$columns[] = 'fed.fed_url';
		$columns[] = 'subscribers';
		if($this->config->item('tags')) {
			$columns[] = 'tag.tag_title';
		}
		$col = $this->reader_library->build_columns($this->router->class.'_subscriptions', $columns, 'fed.fed_title', 'ASC');
		$results = $this->reader_model->get_subscriptions_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_subscriptions');
		$data = array();
		$data['columns'] = $col;
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['subscriptions'] = $this->reader_model->get_subscriptions_rows($flt, $build_pagination['limit'], $build_pagination['start'], $this->router->class.'_subscriptions');

		$content = $this->load->view('subscriptions_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function read($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$content = $this->load->view('subscriptions_read', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->index();
		}
	}

	public function update($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? GROUP BY tag.tag_id ORDER BY tag.tag_title ASC', array($this->member->mbr_id));
			$data['tags'] = array();
			$data['tags'][0] = $this->lang->line('no_tag');
			if($query->num_rows() > 0) {
				foreach($query->result() as $tag) {
					$data['tags'][$tag->tag_id] = $tag->tag_title;
				}
			}

			$this->form_validation->set_rules('sub_title', 'lang:sub_title', '');
			$this->form_validation->set_rules('tag', 'lang:tag', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('sub_title', $this->input->post('sub_title'));
				if($this->input->post('tag') == 0) {
					$this->db->set('tag_id', '');
				} else {
					$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $this->input->post('tag')));
					if($query->num_rows() > 0) {
						$this->db->set('tag_id', $this->input->post('tag'));
					}
				}
				$this->db->where('sub_id', $sub_id);
				$this->db->update('subscriptions');

				//$this->read($sub_id);
				redirect(base_url().'subscriptions');
			}
		} else {
			$this->index();
		}
	}

	public function delete($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_delete', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->where('sub_id', $sub_id);
				$this->db->delete('subscriptions');

				redirect(base_url().'subscriptions');
			}
		} else {
			$this->index();
		}
	}
}
