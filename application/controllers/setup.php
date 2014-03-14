<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
	public function index() {
		if($this->config->item('salt_password')) {
			redirect(base_url().'home');
		}

		$this->load->library(array('form_validation'));

		if(!file_exists('application/config/readerself_config.php')) {
			$fp = fopen('application/config/readerself_config.php', 'w');
			fclose($fp);
		}

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]');
		$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password', 'required');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'required|matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('setup_index', $data, TRUE);
			$this->readerself_library->set_content($content);

		} else {
			if($this->db->dbdriver == 'mysqli') {
				$queries = explode(';', trim(file_get_contents('INSTALLATION.sql')));
				foreach($queries as $query) {
					if($query != '') {
						$this->db->query(str_replace('NOW()', '\''.date('Y-m-d H:i:s').'\'', $query));
					}
				}
			}

			$lines = array();
			$lines['salt_password'] = generate_string(10);
			$lines['ldap'] = FALSE;
			$lines['ldap_server'] = 'ldap://localhost';
			$lines['ldap_port'] = 389;
			$lines['ldap_protocol'] = 3;
			$lines['ldap_rootdn'] = 'cn=Manager,dc=my-domain,dc=com';
			$lines['ldap_rootpw'] = 'secret';
			$lines['ldap_basedn'] = 'dc=my-domain,dc=com';
			$lines['ldap_filter'] = 'mail=[email]';
			$lines['email_protocol'] = 'mail';//mail or smtp
			$lines['smtp_host'] = '';
			$lines['smtp_user'] = '';
			$lines['smtp_pass'] = '';
			$lines['smtp_port'] = 25;

			$fp = fopen('application/config/readerself_config.php', 'w');
			fwrite($fp, '<?php'."\n");
			foreach($lines as $cfg_path => $cfg_value) {
				$this->config->set_item($cfg_path, $cfg_value);
				if(is_numeric($cfg_value)) {
					fwrite($fp, '$config[\''.$cfg_path.'\'] = '.$cfg_value.';'."\n");
				} else {
					fwrite($fp, '$config[\''.$cfg_path.'\'] = \''.$cfg_value.'\';'."\n");
				}
			}
			fclose($fp);

			$this->config->set_item('salt_password', $lines['salt_password']);

			$this->db->set('mbr_email', $this->input->post('mbr_email'));
			$this->db->set('mbr_password', $this->readerself_library->set_salt_password($this->input->post('mbr_password')));
			if($this->readerself_model->count_members() == 0) {
				$this->db->set('mbr_administrator', 1);
			}
			$this->db->set('mbr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('members');
			$mbr_id = $this->db->insert_id();

			$this->readerself_model->connect($mbr_id);

			redirect(base_url().'home');
		}
	}
}
