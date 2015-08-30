<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instagram extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->redirect_uri = base_url().'instagram/callback';
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$content = $this->load->view('instagram_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	public function authorize() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		redirect('https://api.instagram.com/oauth/authorize/?client_id='.$this->config->item('instagram/client_id').'&redirect_uri='.$this->redirect_uri.'&response_type=code');
	}
	public function callback() {
		$url = 'https://api.instagram.com/oauth/access_token';
		$fields = array(
			'client_id' => $this->config->item('instagram/client_id'),
			'client_secret' => $this->config->item('instagram/client_secret'),
			'grant_type' => 'authorization_code',
			'redirect_uri' => $this->redirect_uri,
			'code' => $this->input->get('code'),
		);
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_POSTFIELDS, $fields);
		$result = json_decode(curl_exec($ci));

		if(isset($result->access_token) == 1) {
			$access_token = $result->access_token;

			$query = $this->db->query('SELECT stg.* FROM '.$this->db->dbprefix('settings').' AS stg WHERE stg.stg_code = ? GROUP BY stg.stg_id', array('instagram/access_token'));
			if($query->num_rows() == 0) {
				$this->db->set('stg_value', $access_token);
				$this->db->set('stg_code', 'instagram/access_token');
				$this->db->set('stg_type', 'varchar');
				$this->db->set('stg_is_global', 1);
				$this->db->set('stg_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('settings');
			} else {
				$this->db->set('stg_value', $access_token);
				$this->db->where('stg_code', 'instagram/access_token');
				$this->db->update('settings');
			}
		}

		if($this->axipi_session->userdata('mbr_id')) {
			redirect(base_url().'instagram');
		}
	}
}
