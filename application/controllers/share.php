<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include('thirdparty/feedwriter/Item.php');
include('thirdparty/feedwriter/Feed.php');
include('thirdparty/feedwriter/ATOM.php');

use \FeedWriter\ATOM;

class Share extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function _remap($method, $params = array()) {
		if(method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);
		} else {
			$this->router->set_method('index');
			$this->index($method);
		}
	}
	public function index($token_share = false) {
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.token_share = ? GROUP BY mbr.mbr_id', array($token_share));
		if($query->num_rows() > 0) {
			$member = $query->row();

			$feed = new ATOM;
			$feed->setTitle($this->lang->line('shared_items'));
			$feed->setLink(base_url().'share/'.$token_share);
			$feed->setDate(new DateTime());

			$where = array();
			$bindings = array();

			$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
			$bindings[] = $member->mbr_id;

			$where[] = 'itm.itm_id IN ( SELECT shr.itm_id FROM share AS shr WHERE shr.itm_id = itm.itm_id AND shr.mbr_id = ? )';
			$bindings[] = $member->mbr_id;

			$sql = 'SELECT itm.* FROM items AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id ORDER BY itm.itm_date DESC LIMIT 0,50';
			$query = $this->db->query($sql, $bindings);
			if($query->num_rows() > 0) {
				foreach($query->result() as $itm) {
					$feed_item = $feed->createNewItem();
					$feed_item->setTitle($itm->itm_title);
					$feed_item->setLink($itm->itm_link);
					$feed_item->setDate($itm->itm_date);
					if($itm->itm_author) {
						$feed_item->setAuthor($itm->itm_author);
					}
					$sql = 'SELECT enr.* FROM enclosures AS enr WHERE enr.itm_id = ? GROUP BY enr.enr_id';
					$enclosures = $this->db->query($sql, array($itm->itm_id))->result();
					if($enclosures) {
						foreach($enclosures as $enr) {
							$feed_item->setEnclosure($enr->enr_link, $enr->enr_length, $enr->enr_type);
						}
					}
					$feed_item->setContent($itm->itm_content);
					$feed->addItem($feed_item);
				}
			}

			$feed->printFeed();
		}
		exit(0);
	}
}
