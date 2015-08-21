<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->config->set_item('title', 'Reader Self');
		$this->config->set_item('material-design/colors/background/header', 'teal');
		$this->config->set_item('material-design/colors/background/layout', 'grey-100');
		$this->config->set_item('material-design/colors/background/card', 'white');
		$this->config->set_item('material-design/colors/text/card-title-highlight', 'white');
		$this->config->set_item('material-design/colors/background/card-title-highlight', 'teal');
		$this->config->set_item('material-design/colors/text/card-title', 'black');
		$this->config->set_item('material-design/colors/text/content', 'black');
		$this->config->set_item('material-design/colors/background/button', 'pink');
		$this->config->set_item('material-design/colors/text/button', 'white');
	}
	public function index() {
		if($this->config->item('salt_password') || $this->axipi_session->userdata('setup_done')) {
			redirect(base_url().'home');
		}

		$data = array();
		$data['pdo_drivers'] = PDO::getAvailableDrivers();

		$data['types'] = array();
		if(function_exists('mysqli_connect')) {
			$data['types']['mysqli'] = 'MySQL (Improved Extension)';
		}
		if(in_array('mysql', $data['pdo_drivers'])) {
			$data['types']['pdo_mysql'] = 'MySQL (PDO)';
		}
		if(in_array('sqlite', $data['pdo_drivers'])) {
			$data['types']['pdo_sqlite'] = 'SQLite (PDO)';
		}

		$this->load->library(array('form_validation'));

		if(is_writable('application/config')) {
			if(!file_exists('application/config/database.php')) {
				$fp = fopen('application/config/database.php', 'w');
				fclose($fp);
			}

			if(!file_exists('application/config/readerself_config.php')) {
				$fp = fopen('application/config/readerself_config.php', 'w');
				fclose($fp);
			}
		}

		$this->form_validation->set_rules('database_type', 'lang:database_type', 'required|callback_database_type');

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]');
		$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password', 'required');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'required|matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('setup_index', $data, TRUE);
			$this->readerself_library->set_content($content);

		} else {
			$parameters = array();
			if($this->input->post('database_type') == 'mysqli') {
				$parameters['dsn'] = '';
				$parameters['hostname'] = $this->input->post('database_hostname');
				$parameters['username'] = $this->input->post('database_username');
				$parameters['password'] = $this->input->post('database_password');
				$parameters['database'] = $this->input->post('database_name');
				$parameters['dbdriver'] = 'mysqli';
			}
			if($this->input->post('database_type') == 'pdo_mysql') {
				$parameters['dsn'] = 'mysql:dbname='.$this->input->post('database_name').';host='.$this->input->post('database_hostname');
				$parameters['hostname'] = '';
				$parameters['username'] = $this->input->post('database_username');
				$parameters['password'] = $this->input->post('database_password');
				$parameters['database'] = '';
				$parameters['dbdriver'] = 'pdo';
			}
			if($this->input->post('database_type') == 'pdo_sqlite') {
				$parameters['dsn'] = 'sqlite:application/database/readerself.sqlite';
				$parameters['hostname'] = '';
				$parameters['username'] = '';
				$parameters['password'] = '';
				$parameters['database'] = '';
				$parameters['dbdriver'] = 'pdo';
			}
			$content_view = $this->load->view('setup_database', $parameters, TRUE);
			$fp = fopen('application/config/database.php', 'w');
			fwrite($fp, '<?php'."\n");
			fwrite($fp, $content_view);
			fclose($fp);

			if(function_exists('opcache_invalidate')) {
				opcache_invalidate('application/config/database.php', true);
			}

			$this->load->database();

			if($this->input->post('database_type') == 'mysqli' || $this->input->post('database_type') == 'pdo_mysql') {
				$queries = explode(';', trim(file_get_contents('application/database/installation-mysql.sql')));
			}
			if($this->input->post('database_type') == 'pdo_sqlite') {
				$queries = explode(';', trim(file_get_contents('application/database/installation-sqlite.sql')));
			}
			foreach($queries as $query) {
				if($query != '') {
					$this->db->query(str_replace('NOW()', '\''.date('Y-m-d H:i:s').'\'', $query));
				}
			}

			$parameters = array();
			$parameters['salt_password'] = generate_string(10);
			$content_view = $this->load->view('setup_config', $parameters, TRUE);
			$fp = fopen('application/config/readerself_config.php', 'w');
			fwrite($fp, '<?php'."\n");
			fwrite($fp, $content_view);
			fclose($fp);

			if(function_exists('opcache_invalidate')) {
				opcache_invalidate('application/config/readerself_config.php', true);
			}

			$this->config->load('readerself_config', false, true);

			$this->db->set('mbr_email', $this->input->post('mbr_email'));
			$this->db->set('mbr_password', $this->readerself_library->set_salt_password($this->input->post('mbr_password')));
			if($this->readerself_model->count_members() == 0) {
				$this->db->set('mbr_administrator', 1);
			}
			$this->db->set('mbr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('members');
			$mbr_id = $this->db->insert_id();

			$this->axipi_session->set_userdata('setup_done', true);

			$this->readerself_model->connect($mbr_id);

			redirect(base_url().'setup/confirm');
		}
	}
	public function confirm() {
		$data = array();

		$content = $this->load->view('setup_confirm', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	function database_type() {
		if($this->input->post('database_type') == 'mysqli' || $this->input->post('database_type') == 'pdo_mysql') {
			if($this->input->post('database_username') == '' || $this->input->post('database_password') == '') {
				$this->form_validation->set_message('database_type', $this->lang->line('callback_database_username_password'));
				return FALSE;
			}
			if($this->input->post('database_type') == 'mysqli') {
				try {
					$mysqli = new mysqli($this->input->post('database_hostname'), $this->input->post('database_username'), $this->input->post('database_password'), $this->input->post('database_name'));
					if($mysqli->connect_error) {
						$this->form_validation->set_message('database_type', $mysqli->connect_error);
						return FALSE;
					}
				} catch(Exception $e) {
				}
			}
			if($this->input->post('database_type') == 'pdo_mysql') {
				try {
					$pdo = new PDO('mysql:dbname='.$this->input->post('database_name').';host='.$this->input->post('database_hostname'), $this->input->post('database_username'), $this->input->post('database_password'));
				} catch(PDOException $e) {
					$this->form_validation->set_message('database_type', $e->getMessage());
					return FALSE;
				}
			}
		}
		if($this->input->post('database_type') == 'pdo_sqlite') {
			if(!is_writable('application/database')) {
				$this->form_validation->set_message('database_type', 'Directory application/database not writable');
				return FALSE;
			}
			try {
				$pdo = new PDO('sqlite:application/database/readerself.sqlite');
			} catch(PDOException $e) {
				$this->form_validation->set_message('database_type', $e->getMessage());
				return FALSE;
			}
		}
		return TRUE;
	}
}
