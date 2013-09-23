<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library(array('form_validation'));

		if(!$this->config->item('ldap')) {
			$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]|callback_email');
			$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
			$this->form_validation->set_rules('mbr_password', 'lang:mbr_password');
			$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'matches[mbr_password]');
		}
		$this->form_validation->set_rules('mbr_nickname', 'lang:mbr_nickname', 'alpha_dash|max_length[255]|callback_nickname');
		if($this->config->item('gravatar')) {
			$this->form_validation->set_rules('mbr_gravatar', 'lang:gravatar', 'valid_email|max_length[255]');
		}
		$this->form_validation->set_rules('mbr_description', 'lang:description');

		if($this->form_validation->run() == FALSE) {
			$data = array();

			$content = $this->load->view('profile_index', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			if(!$this->config->item('ldap')) {
				$this->db->set('mbr_email', $this->input->post('mbr_email'));
				if($this->input->post('mbr_password') != '' && $this->input->post('mbr_password_confirm') != '') {
					$this->db->set('mbr_password', $this->reader_library->set_salt_password($this->input->post('mbr_password')));
				}
			}
			$this->db->set('mbr_nickname', $this->input->post('mbr_nickname'));
			if($this->config->item('gravatar')) {
				$this->db->set('mbr_gravatar', $this->input->post('mbr_gravatar'));
			}
			$this->db->set('mbr_description', $this->input->post('mbr_description'));
			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->update('members');

			redirect(base_url().'profile');
		}
	}
	public function delete() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$this->form_validation->set_rules('confirm', 'lang:confirm', 'required|callback_confirm');
		if($this->form_validation->run() == FALSE) {

			$data['connections_total'] = $this->db->query('SELECT COUNT(DISTINCT(cnt.cnt_id)) AS ref_value FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

			$data['subscriptions_total'] = $this->db->query('SELECT COUNT(DISTINCT(sub.sub_id)) AS ref_value FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL', array($this->member->mbr_id))->row()->ref_value;

			$data['folders_total'] = $this->db->query('SELECT COUNT(DISTINCT(flr.flr_id)) AS ref_value FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

			$data['read_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(hst.itm_id)) AS ref_value FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.hst_real = ? AND hst.mbr_id = ?', array(1, $this->member->mbr_id))->row()->ref_value;

			$data['starred_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(fav.itm_id)) AS ref_value FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

			$data['shared_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(shr.itm_id)) AS ref_value FROM '.$this->db->dbprefix('share').' AS shr WHERE shr.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

			$content = $this->load->view('profile_delete', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('connections');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('favorites');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('folders');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('history');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('share');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('subscriptions');

			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->delete('members');

			$this->db->query('OPTIMIZE TABLE connections, favorites, folders, history, share, subscriptions, members');

			$this->reader_model->logout();

			redirect(base_url());
		}
	}
	public function connections() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		include('thirdparty/PhpUserAgent/UserAgentParser.php');

		$data['connections'] = $this->db->query('SELECT cnt.*, DATE_ADD(cnt.cnt_datecreated, INTERVAL ? HOUR) AS cnt_datecreated FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.token_connection IS NOT NULL AND cnt.mbr_id = ? GROUP BY cnt.cnt_id ORDER BY cnt.cnt_id DESC LIMIT 0,30', array($this->session->userdata('timezone'), $this->member->mbr_id))->result();

		$content = $this->load->view('profile_connections', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function logout_purge() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->db->where('mbr_id', $this->member->mbr_id);
		$this->db->where('token_connection !=', $this->member->token_connection);
		$this->db->delete('connections');

		redirect(base_url().'profile/connections');
	}
	public function email() {
		if($this->input->post('mbr_email')) {
			if($this->member->mbr_email == 'example@example.com') {
				$this->form_validation->set_message('email', '<i class="icon icon-bell"></i>Demo account');
				return FALSE;
			}

			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? AND mbr.mbr_email != ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_email'), $this->member->mbr_email));
			if($query->num_rows() > 0) {
				$this->form_validation->set_message('email', $this->lang->line('callback_email'));
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
	public function confirm() {
		if($this->member->mbr_email == 'example@example.com') {
			$this->form_validation->set_message('confirm', '<i class="icon icon-bell"></i>Demo account');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function nickname() {
		if($this->input->post('mbr_nickname')) {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_nickname = ? AND mbr.mbr_nickname != ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_nickname'), $this->member->mbr_nickname));
			if($query->num_rows() > 0) {
				$this->form_validation->set_message('nickname', $this->lang->line('callback_nickname'));
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}
