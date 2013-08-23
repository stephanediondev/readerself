<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trends extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

		$data['subscriptions_total'] = $this->db->query('SELECT COUNT(DISTINCT(sub.sub_id)) AS ref_value FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL', array($this->member->mbr_id))->row()->ref_value;

		$data['read_items_30'] = $this->db->query('SELECT COUNT(DISTINCT(hst.itm_id)) AS ref_value FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.hst_datecreated >= ? AND hst.mbr_id = ?', array($date_ref, $this->member->mbr_id))->row()->ref_value;

		$data['starred_items_30'] = $this->db->query('SELECT COUNT(DISTINCT(fav.itm_id)) AS ref_value FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.fav_datecreated >= ? AND fav.mbr_id = ?', array($date_ref, $this->member->mbr_id))->row()->ref_value;

		$data['date_first_read'] = $this->db->query('SELECT MIN(hst.hst_datecreated) AS ref_value FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

		$data['read_items_total'] = $this->db->query('SELECT COUNT(DISTINCT(hst.itm_id)) AS ref_value FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?', array($this->member->mbr_id))->row()->ref_value;

		$data['tables'] = '';

		$legend = array();
		$values = array();
		$query = $this->db->query('SELECT fed.fed_title AS ref, sub.sub_id AS id, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = fed.fed_id WHERE itm.itm_datecreated >= ? AND sub.mbr_id = ? AND fed.fed_title IS NOT NULL GROUP BY id ORDER BY nb DESC LIMIT 0,12', array($date_ref, $this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$legend[] = $row->ref;
				$values[] = $row->nb;
			}
		}
		$data['tables'] .= build_table_repartition('Most active subscriptions*', $values, $legend);

		$legend = array();
		$values = array();
		$query = $this->db->query('SELECT fed.fed_title AS ref, sub.sub_id AS id, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = fed.fed_id AND itm.itm_datecreated >= ? WHERE sub.mbr_id = ? AND fed.fed_title IS NOT NULL GROUP BY id ORDER BY nb ASC LIMIT 0,12', array($date_ref, $this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$legend[] = $row->ref;
				$values[] = $row->nb;
			}
		}
		$data['tables'] .= build_table_repartition('Less active subscriptions*', $values, $legend);

		$days = array(7=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday');
		$legend = array();
		$values = array();
		$query = $this->db->query('SELECT IF(DATE_FORMAT(hst.hst_datecreated, \'%w\') = 0, 7, DATE_FORMAT(hst.hst_datecreated, \'%w\')) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.hst_datecreated >= ? AND hst.mbr_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,12', array($date_ref, $this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$legend[] = $days[$row->ref];
				$values[] = $row->nb;
			}
		}
		$data['tables'] .= build_table_repartition('Items read by day of the week*', $values, $legend);

		$legend = array();
		$values = array();
		$query = $this->db->query('SELECT DATE_FORMAT(hst.hst_datecreated, \'%H\') AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.hst_datecreated >= ? AND hst.mbr_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,12', array($date_ref, $this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$legend[] = intval($row->ref).'h';
				$values[] = $row->nb;
			}
		}
		$data['tables'] .= build_table_repartition('Items read per time of day*', $values, $legend);

		$legend = array();
		$values = array();
		$query = $this->db->query('SELECT SUBSTRING(hst.hst_datecreated, 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$legend[] = $row->ref;
				$values[] = $row->nb;
			}
		}
		$data['tables'] .= build_table_progression('Items read per month', $values, $legend);

		$content = $this->load->view('trends_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
}
