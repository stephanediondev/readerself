<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msapplication extends CI_Controller {
	public function badge($token_msapplication = '') {
		header('content-type: text/xml; charset=UTF-8');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.token_msapplication = ? GROUP BY mbr.mbr_id', array($token_msapplication));
		if($query->num_rows() > 0) {
			$member = $query->row();

			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ?';

			echo '<badge value="'.$this->db->query($sql, array($member->mbr_id, $member->mbr_id))->row()->count.'" />';
		} else {
			echo '<badge value="error" />';
		}
		exit(0);
	}
	public function pin() {
		$this->readerself_library->set_template('_pin');
		$this->readerself_library->set_content('');
	}
}
