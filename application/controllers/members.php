<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id') || !$this->config->item('members_list')) {
			redirect(base_url());
		}

		$filters = array();
		$filters[$this->router->class.'_members_mbr_nickname'] = array('mbr.mbr_nickname', 'like');
		$flt = $this->readerself_library->build_filters($filters);
		if($this->member->mbr_administrator == 0) {
			$flt[] = 'mbr.mbr_nickname IS NOT NULL';
		}
		$results = $this->readerself_model->get_members_total($flt);
		$build_pagination = $this->readerself_library->build_pagination($results->count, 20, $this->router->class.'_members');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['members'] = $this->readerself_model->get_members_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'mbr.mbr_nickname ASC');

		$content = $this->load->view('members_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	public function delete($mbr_id) {
		if(!$this->session->userdata('mbr_id') || $this->member->mbr_administrator == 0) {
			redirect(base_url());
		}
		$mbr = $this->readerself_model->get_member_row($mbr_id);
		if($mbr) {
			$data = array();

			$this->mbr_delete_email = $mbr->mbr_email;

			$this->load->library('form_validation');
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required|callback_confirm');
			if($this->form_validation->run() == FALSE) {
				$data['mbr'] = $mbr;

				$data['connections_total'] = $this->db->query('SELECT COUNT(DISTINCT(cnt.cnt_id)) AS ref_value FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.mbr_id = ?', array($mbr_id))->row()->ref_value;

				$data['subscriptions_total'] = $this->db->query('SELECT COUNT(DISTINCT(sub.sub_id)) AS ref_value FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL', array($mbr_id))->row()->ref_value;

				$data['folders_total'] = $this->db->query('SELECT COUNT(DISTINCT(flr.flr_id)) AS ref_value FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ?', array($mbr_id))->row()->ref_value;

				$data['read_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(hst.itm_id)) AS ref_value FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.hst_real = ? AND hst.mbr_id = ?', array(1, $mbr_id))->row()->ref_value;

				$data['starred_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(fav.itm_id)) AS ref_value FROM '.$this->db->dbprefix('favorites').' AS fav LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = fav.itm_id WHERE fav.mbr_id = ? AND itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )', array($mbr_id, $mbr_id))->row()->ref_value;

				$data['shared_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(shr.itm_id)) AS ref_value FROM '.$this->db->dbprefix('share').' AS shr LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = shr.itm_id WHERE shr.mbr_id = ? AND itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )', array($mbr_id, $mbr_id))->row()->ref_value;

				$content = $this->load->view('members_delete', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('connections');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('favorites');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('folders');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('history');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('share');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('subscriptions');

				$this->db->where('mbr_id', $mbr_id);
				$this->db->delete('members');

				$this->db->query('OPTIMIZE TABLE connections, favorites, folders, history, share, subscriptions, members');

				if($mbr_id == $this->member->mbr_id) {
					$this->readerself_model->logout();
				}

				redirect(base_url().'members');
			}
		}
	}

	public function confirm() {
		if($this->mbr_delete_email == 'example@example.com') {
			$this->form_validation->set_message('confirm', '<i class="icon icon-bell"></i>Demo account');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
