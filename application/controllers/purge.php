<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purge extends CI_Controller {
	public function index() {
		$content = '';

		$where = array();
		$bindings = array();

		$where[] = 'itm_deleted = 0';

		$where[] = 'itm_date < ?';
		$bindings[] = date('Y-m-d H:i:s', time() - 3600 * 24 * 30 * 6);

		$where[] = 'itm_id NOT IN ( SELECT fav.itm_id FROM '.$this->db->dbprefix('favorites').' AS fav )';
		$where[] = 'itm_id NOT IN ( SELECT shr.itm_id FROM '.$this->db->dbprefix('share').' AS shr )';

		$sql = 'UPDATE '.$this->db->dbprefix('items').' SET itm_deleted = 1, itm_content = NULL WHERE '.implode(' AND ', $where);
		$query = $this->db->query($sql, $bindings);

		if($this->db->dbdriver == 'mysqli') {
			$this->db->query('OPTIMIZE TABLE items');
		}

		$this->readerself_library->set_content($content);
	}
}
