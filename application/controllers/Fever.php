<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fever extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index() {
		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$content = array();
		$content['api_version'] = 2;
		$content['auth'] = 'TODO';
		$content['last_refreshed_on_time'] = 'TODO';

		$member_id = 1;

		if(isset($_GET['groups']) == 1) {
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

		if(isset($_GET['feeds']) == 1) {
			$result = $this->db->query('SELECT fed.* FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? GROUP BY fed.fed_id', array($member_id))->result();
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
						'last_updated_on_time' => 'TODO',
					);
				}
			}
		}

		if(isset($_GET['groups']) == 1 || isset($_GET['feeds']) == 1) {
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

		if(isset($_GET['items']) == 1) {
			$content['total_items'] = 'TODO';

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
						'created_on_time' => $row->itm_datecreated,
					);
				}
			}
		}

		/*if(isset($_GET['mark']) == 1) {
			$where = array();
			$bindings = array();

			if(isset($_GET['item']) == 1) {
				$where[] = 'itm_id IN ( SELECT sub.itm_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? ) AND itm_id = ?';
				$bindings[] = $member_id;
				$bindings[] = $_GET['item'];
			}
			if(isset($_GET['feed']) == 1) {
				$where[] = 'itm_id IN ( SELECT sub.itm_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.fed_id = ? )';
				$bindings[] = $member_id;
				$bindings[] = $_GET['feed'];
			}
			if(isset($_GET['group']) == 1) {
				$where[] = 'itm_id IN ( SELECT sub.itm_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.flr_id = ? )';
				$bindings[] = $member_id;
				$bindings[] = $_GET['group'];
			}

			$result = $this->db->query('SELECT itm.* FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id', $bindings)->result();
			if($result) {
				foreach($result as $row) {
				}
			}
		}*/

		$this->readerself_library->set_content($content);
	}
}
