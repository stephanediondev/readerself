<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fever extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index() {
		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$content = array();

		$api_key = $_POST['api_key'];

		$row = $this->db->query('SELECT tok.mbr_id FROM '.$this->db->dbprefix('tokens').' AS tok WHERE tok.tok_type = ? AND tok.tok_value = ? AND tok.tok_sandbox = ? GROUP BY tok.tok_id', array('fever', $api_key, 0))->row();
		if($row) {
			$member_id = $row->mbr_id;
			$content['auth'] = 1;

			$last_item = $this->db->query('SELECT MAX(itm.itm_date) AS itm_date FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )', array($member_id))->row();
			if($last_item) {
				$content['last_refreshed_on_time'] = date('U', strtotime($last_item->itm_date));
			}
		} else {
			$member_id = false;
			$content['auth'] = 0;
		}

		$content['api_version'] = 2;

		if($member_id && isset($_GET['groups']) == 1) {
			$result = $this->db->query('SELECT flr.flr_id, flr.flr_title FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id', array($member_id))->result();
			if($result) {
				$content['groups'] = array();
				foreach($result as $row) {
					$content['groups'][] = array(
						'id' => $row->flr_id,
						'title' => $row->flr_title,
					);
				}
			}
		}

		if($member_id && isset($_GET['feeds']) == 1) {
			$result = $this->db->query('SELECT fed.*, ( SELECT MAX(itm.itm_date) FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = fed.fed_id) AS last_updated_on_time FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? GROUP BY fed.fed_id', array($member_id))->result();
			if($result) {
				$content['feeds'] = array();
				foreach($result as $row) {
					$content['feeds'][] = array(
						'id' => $row->fed_id,
						'favicon_id' => $row->fed_id,
						'title' => $row->fed_title,
						'url' => $row->fed_link,
						'site_url' => $row->fed_url,
						'is_spark' => 0,
						'last_updated_on_time' => date('U', strtotime($row->last_updated_on_time)),
					);
				}
			}
		}

		if($member_id && isset($_GET['groups']) == 1 || isset($_GET['feeds']) == 1) {
			$result = $this->db->query('SELECT flr.flr_id, (SELECT GROUP_CONCAT(DISTINCT fed.fed_id SEPARATOR \',\') FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.flr_id = flr.flr_id) AS feeds FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id', array($member_id, $member_id))->result();
			if($result) {
				$content['feeds_groups'] = array();
				foreach($result as $row) {
					$content['feeds_groups'][] = array(
						'group_id' => $row->flr_id,
						'feed_ids' => $row->feeds,
					);
				}
			}
		}

		if($member_id && isset($_GET['items']) == 1) {
			$where = array();
			$bindings = array();

			$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
			$bindings[] = $member_id;

			$content['total_items'] = $this->db->query('SELECT COUNT(DISTINCT(itm.itm_id)) AS total FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where), $bindings)->row()->total;

			$where = array();
			$bindings = array();

			$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
			$bindings[] = $member_id;

			$order = 'ASC';

			if(isset($_GET['since_id']) == 1) {
				$where[] = 'itm.itm_id > ?';
				$bindings[] = $_GET['since_id'];
			}
			if(isset($_GET['max_id']) == 1) {
				$where[] = 'itm.itm_id < ?';
				$bindings[] = $_GET['max_id'];

				$order = 'DESC';
			}
			if(isset($_GET['with_ids']) == 1) {
				$where[] = 'itm.itm_id IN('.$_GET['with_ids'].')';
			}

			$result = $this->db->query('SELECT itm.* FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id ORDER BY itm.itm_id '.$order.' LIMIT 0,50', $bindings)->result();
			if($result) {
				$content['items'] = array();
				foreach($result as $row) {
					$row->author = false;
					if($row->auh_id) {
						$sql = 'SELECT auh.* FROM '.$this->db->dbprefix('authors').' AS auh WHERE auh.auh_id = ? GROUP BY auh.auh_id';
						$author = $this->db->query($sql, array($row->auh_id))->row();
						if($author) {
							$row->author = $author->auh_title;
						}
					}

					$sql = 'SELECT fav.* FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id';
					$query = $this->db->query($sql, array($row->itm_id, $member_id));
					if($query->num_rows() > 0) {
						$row->is_saved = 1;
					} else {
						$row->is_saved = 0;
					}

					$sql = 'SELECT hst.* FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id';
					$query = $this->db->query($sql, array($row->itm_id, $member_id));
					if($query->num_rows() > 0) {
						$row->is_read = 1;
					} else {
						$row->is_read = 0;
					}

					$content['items'][] = array(
						'id' => $row->itm_id,
						'feed_id' => $row->fed_id,
						'title' => $row->itm_title,
						'author' => $row->author,
						'html' => $row->itm_content,
						'url' => $row->itm_link,
						'is_saved' => $row->is_saved,
						'is_read' => $row->is_read,
						'created_on_time' => date('U', strtotime($row->itm_date)),
					);
				}
			}
		}

		$add_unread_item_ids = false;
		$add_saved_item_ids = false;

		if($member_id && isset($_POST['as']) == 1 && isset($_POST['id']) == 1 && isset($_POST['mark']) == 1) {
			$where = array();
			$bindings = array();

			$bindings[] = $member_id;
			if($_POST['as'] == 'read' || $_POST['as'] == 'saved') {
				$bindings[] = date('Y-m-d H:i:s');
			}

			if($_POST['mark'] == 'item') {
				$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? ) AND itm_id = ?';
				$bindings[] = $member_id;
				$bindings[] = $_POST['id'];
			}
			if($_POST['mark'] == 'feed') {
				$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.fed_id = ? )';
				$bindings[] = $member_id;
				$bindings[] = $_POST['id'];
			}
			if($_POST['mark'] == 'group') {
				$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.flr_id = ? )';
				$bindings[] = $member_id;
				$bindings[] = $_POST['id'];
			}

			if(isset($_POST['before'])) {
				$where[] = 'itm.itm_date < ?';
				$bindings[] = date('Y-m-d H:i:s', $_POST['before']);
			}

			if($_POST['as'] == 'read') {
				$add_unread_item_ids = true;

				$where[] = 'itm.itm_id NOT IN ( SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = itm.itm_id AND hst.mbr_id = ? )';
				$bindings[] = $member_id;

				$sql = 'INSERT INTO '.$this->db->dbprefix('history').' (itm_id, mbr_id, hst_real, hst_datecreated)
				SELECT itm.itm_id AS itm_id, ? AS mbr_id, \'0\' AS hst_real, ? AS hst_datecreated FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id';
				$query = $this->db->query($sql, $bindings);
			}

			if($_POST['as'] == 'unread') {
				$add_unread_item_ids = true;

				$sql = 'DELETE FROM '.$this->db->dbprefix('history').' WHERE mbr_id = ? AND itm_id IN (SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id)';
				$query = $this->db->query($sql, $bindings);
			}

			if($_POST['as'] == 'saved') {
				$add_saved_item_ids = true;

				$where[] = 'itm.itm_id NOT IN ( SELECT fav.itm_id FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = itm.itm_id AND fav.mbr_id = ? )';
				$bindings[] = $member_id;

				$sql = 'INSERT INTO '.$this->db->dbprefix('favorites').' (itm_id, mbr_id, fav_datecreated)
				SELECT itm.itm_id AS itm_id, ? AS mbr_id, ? AS hst_datecreated FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id';
				$query = $this->db->query($sql, $bindings);
			}

			if($_POST['as'] == 'unsaved') {
				$add_saved_item_ids = true;

				$sql = 'DELETE FROM '.$this->db->dbprefix('favorites').' WHERE mbr_id = ? AND itm_id IN (SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id)';
				$query = $this->db->query($sql, $bindings);
			}
		}

		if($member_id && (isset($_GET['unread_item_ids']) == 1 || $add_unread_item_ids)) {
			$where = array();
			$bindings = array();

			$bindings[] = $member_id;

			$where[] = 'hst.hst_id IS NULL';

			$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
			$bindings[] = $member_id;

			$result = $this->db->query('SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ? WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id', $bindings)->result();
			if($result) {
				$items = array();
				foreach($result as $row) {
					$items[] = $row->itm_id;
				}
			}
			$content['unread_item_ids'] = implode(',', $items);
		}

		if($member_id && (isset($_GET['saved_item_ids']) == 1 || $add_saved_item_ids)) {
			$where = array();
			$bindings = array();

			$where[] = 'fav.mbr_id = ?';
			$bindings[] = $member_id;

			$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
			$bindings[] = $member_id;

			$result = $this->db->query('SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('favorites').' AS fav ON fav.itm_id = itm.itm_id WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id', $bindings)->result();
			if($result) {
				$items = array();
				foreach($result as $row) {
					$items[] = $row->itm_id;
				}
			}
			$content['saved_item_ids'] = implode(',', $items);
		}

		//file_put_contents('fever.log', 'GET: '.var_export($_GET, true)."\r\n".'POST: '.var_export($_POST, true)."\r\n\r\n", FILE_APPEND | LOCK_EX);

		$this->readerself_library->set_content($content);
	}
	public function configure() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['token'] = $this->readerself_model->get_token('fever', $member_id, false);

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('fever_configure', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			$api_key = md5($this->member->mbr_email.':'.$this->input->post('mbr_password'));

			$this->readerself_model->set_token('fever', $member_id, $api_key, false);

			redirect(base_url().'fever/configure');
		}
	}
}
