<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purge extends CI_Controller {
	public function index() {
		$content = '';

		$where = array();
		$bindings = array();

		$where[] = 'itm_deleted = 0';

		$where[] = 'DATE_ADD(itm_date, INTERVAL 6 MONTH) < ?';
		$bindings[] = date('Y-m-d H:i:s');

		$where[] = 'itm_id NOT IN ( SELECT fav.itm_id FROM '.$this->db->dbprefix('favorites').' AS fav )';
		$where[] = 'itm_id NOT IN ( SELECT shr.itm_id FROM '.$this->db->dbprefix('share').' AS shr )';

		$sql = 'UPDATE '.$this->db->dbprefix('items').' SET itm_deleted = 1, itm_content = NULL WHERE '.implode(' AND ', $where);
		$query = $this->db->query($sql, $bindings);

		$this->db->query('OPTIMIZE TABLE items');

		$this->readerself_library->set_content($content);
	}
}
