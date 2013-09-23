<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reader_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	function login($email, $password) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->unset_userdata('mbr_id');

			if($this->config->item('ldap')) {
				$ldap_connect = ldap_connect($this->config->item('ldap_server'), $this->config->item('ldap_port'));
				if($ldap_connect) {
					ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, $this->config->item('ldap_protocol'));
					ldap_set_option($ldap_connect, LDAP_OPT_REFERRALS, 0); 
					if(ldap_bind($ldap_connect, $this->config->item('ldap_rootdn'), $this->config->item('ldap_rootpw'))) {
						$ldap_search = ldap_search($ldap_connect, $this->config->item('ldap_basedn'), str_replace('[email]', $email, $this->config->item('ldap_filter')));
						if($ldap_search) {
							$ldap_get_entries = ldap_get_entries($ldap_connect, $ldap_search);
							if($ldap_get_entries['count'] > 0) {
								try {
									if(ldap_bind($ldap_connect, $ldap_get_entries[0]['dn'], $password)) {
										$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($email));
										if($query->num_rows() > 0) {
											$member = $query->row();
											$this->db->set('mbr_password', $this->reader_library->set_salt_password($password));
											$this->db->where('mbr_id', $member->mbr_id);
											$this->db->update('members');
	
										} else {
											$this->db->set('mbr_email', $email);
											$this->db->set('mbr_password', $this->reader_library->set_salt_password($password));
											$this->db->set('mbr_datecreated', date('Y-m-d H:i:s'));
											$this->db->insert('members');
											$member = $this->get($this->db->insert_id());
										}
	
										$this->connect($member->mbr_id);
										return TRUE;
									}
								} catch(Exception $e) {
								}
							}
						}
					}
					ldap_unbind($ldap_connect);
				}

			} else {
				$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($email));
				if($query->num_rows() > 0) {
					$member = $query->row();
					if($this->reader_library->set_salt_password($password) == $member->mbr_password) {
						$this->connect($member->mbr_id);
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}
	function connect($mbr_id) {
		$token_connection = sha1(uniqid($mbr_id, 1).mt_rand());
		$this->db->set('mbr_id', $mbr_id);
		$this->db->set('token_connection', $token_connection);
		$this->db->set('cnt_ip', $this->input->ip_address());
		$this->db->set('cnt_agent', $this->input->user_agent());
		$this->db->set('cnt_datecreated', date('Y-m-d H:i:s'));
		$this->db->insert('connections');

		$this->session->set_userdata('mbr_id', $mbr_id);
		$this->input->set_cookie('token_connection', $token_connection, 3600 * 24 * 30, NULL, '/', NULL, NULL);
	}
	function logout() {
		if($this->session->userdata('mbr_id') && $this->input->cookie('token_connection')) {
			$this->db->where('token_connection', $this->input->cookie('token_connection'));
			$this->db->where('mbr_id', $this->session->userdata('mbr_id'));
			$this->db->delete('connections');
		}

		$this->session->unset_userdata('mbr_id');
		$this->input->set_cookie('token_connection', NULL, 0, NULL, '/', NULL, NULL);
	}
	function get($mbr_id) {
		$member = false;
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_id = ? GROUP BY mbr.mbr_id', array($mbr_id));
		if($query->num_rows() > 0) {
			$member = $query->row();

			if(!$member->token_share) {
				$member->token_share = sha1(uniqid($member->mbr_id, 1).mt_rand());
				$this->db->set('token_share', $member->token_share);
				$this->db->where('mbr_id', $member->mbr_id);
				$this->db->update('members');
			}

			$query = $this->db->query('SELECT cnt.* FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.mbr_id = ? AND token_connection IS NOT NULL AND token_connection = ? GROUP BY cnt.cnt_id', array($mbr_id, $this->input->cookie('token_connection')));
			if($query->num_rows() > 0) {
				$member->token_connection = $query->row()->token_connection;
			} else {
				$member->token_connection = false;
			}
		}
		return $member;
	}
	function count_members() {
		return $this->db->query('SELECT COUNT(DISTINCT(mbr.mbr_id)) AS count FROM '.$this->db->dbprefix('members').' AS mbr')->row()->count;
	}

	function get_members_total($flt) {
		$query = $this->db->query('SELECT COUNT(mbr.mbr_id) AS count FROM '.$this->db->dbprefix('members').' mbr WHERE '.implode(' AND ', $flt));
		return $query->row();
	}
	function get_members_rows($flt, $num, $offset, $order) {
		$members = false;
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE '.implode(' AND ', $flt).' GROUP BY mbr.mbr_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num);
		if($query->num_rows() > 0) {
			$members = array();
			foreach($query->result() as $mbr) {
				if($mbr->mbr_id != $this->member->mbr_id) {
					$subscriptions_common = $this->db->query('SELECT COUNT(DISTINCT(sub.fed_id)) AS count FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ? ) AND sub.mbr_id = ?', array($this->member->mbr_id, $mbr->mbr_id))->row()->count;
				} else {
					$subscriptions_common = false;
				}
				$mbr->subscriptions_common = $subscriptions_common;

				$mbr->shared_items = $this->db->query('SELECT COUNT(DISTINCT(shr.shr_id)) AS count FROM '.$this->db->dbprefix('share').' AS shr WHERE shr.mbr_id = ?', array($mbr->mbr_id))->row()->count;

				$members[] = $mbr;
			}
		}
		return $members;
	}
	function get_member_row($mbr_id) {
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_nickname IS NOT NULL AND mbr.mbr_id = ? GROUP BY mbr.mbr_id', array($mbr_id));
		return $query->row();
	}

	function get_subscriptions_total($flt) {
		$query = $this->db->query('SELECT COUNT(sub.sub_id) AS count FROM '.$this->db->dbprefix('subscriptions').' sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE '.implode(' AND ', $flt));
		return $query->row();
	}
	function get_subscriptions_rows($flt, $num, $offset, $order) {
		$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

		$subscriptions = false;
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, DATE_ADD(sub.sub_datecreated, INTERVAL ? HOUR) AS sub_datecreated, sub.sub_id, sub.sub_title, sub.sub_priority, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE '.implode(' AND ', $flt).' GROUP BY sub.sub_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($timezone, $timezone));
		if($query->num_rows() > 0) {
			$subscriptions = array();
			foreach($query->result() as $sub) {

				$sub->categories = false;
				if($this->config->item('tags')) {
					$categories = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND sub.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,10', array($date_ref, $this->member->mbr_id, $sub->sub_id))->result();
					if($categories) {
						$sub->categories = array();
						foreach($categories as $cat) {
							$sub->categories[] = $cat->ref;
						}
					}
				}
				$subscriptions[] = $sub;
			}
		}
		return $subscriptions;
	}
	function get_subscription_row($sub_id) {
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, sub.sub_id, sub.sub_title, sub.sub_priority, sub.sub_direction, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($timezone, $this->member->mbr_id, $sub_id));
		return $query->row();
	}

	function get_folders_total($flt) {
		$query = $this->db->query('SELECT COUNT(flr.flr_id) AS count FROM '.$this->db->dbprefix('folders').' AS flr WHERE '.implode(' AND ', $flt));
		return $query->row();
	}
	function get_folders_rows($flt, $num, $offset, $order) {
		$query = $this->db->query('SELECT flr.*, (SELECT COUNT(DISTINCT(count_sub.sub_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.flr_id = flr.flr_id) AS subscriptions FROM '.$this->db->dbprefix('folders').' AS flr WHERE '.implode(' AND ', $flt).' GROUP BY flr.flr_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num);
		return $query->result();
	}
	function get_flr_row($flr_id) {
		$query = $this->db->query('SELECT flr.*, (SELECT COUNT(DISTINCT(count_sub.sub_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.flr_id = flr.flr_id) AS subscriptions FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $flr_id));
		return $query->row();
	}

	function get_feeds_total($flt) {
		$query = $this->db->query('SELECT COUNT(DISTINCT(fed.fed_id)) AS count FROM '.$this->db->dbprefix('feeds').' fed WHERE '.implode(' AND ', $flt).' AND fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?)', array($this->member->mbr_id));
		return $query->row();
	}
	function get_feeds_rows($flt, $num, $offset, $order) {
		$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

		$feeds = false;
		$query = $this->db->query('SELECT fed.*, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = fed.fed_id) AS subscribers FROM '.$this->db->dbprefix('feeds').' AS fed WHERE '.implode(' AND ', $flt).' AND fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?) GROUP BY fed.fed_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			$feeds = array();
			foreach($query->result() as $fed) {

				$fed->categories = false;
				if($this->config->item('tags')) {
					$categories = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND itm.fed_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,10', array($date_ref, $fed->fed_id))->result();
					if($categories) {
						$fed->categories = array();
						foreach($categories as $cat) {
							$fed->categories[] = $cat->ref;
						}
					}
				}
				$feeds[] = $fed;
			}
		}
		return $feeds;
	}
	function get_feed_row($fed_id) {
		$query = $this->db->query('SELECT fed.*, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = fed.fed_id) AS subscribers FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id = ? GROUP BY fed.fed_id', array($fed_id));
		return $query->row();
	}
	function count_unread($type, $id = false) {
		if($type == 'all') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'priority') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ? AND sub.sub_priority = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, 1))->row()->count;
		}
		if($type == 'geolocation') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND itm.itm_latitude IS NOT NULL AND itm.itm_longitude IS NOT NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'audio') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('enclosures').' AS enr ON enr.itm_id = itm.itm_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ? AND enr.enr_type LIKE ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, 'audio/%'))->row()->count;
		}
		if($type == 'nofolder') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
			WHERE sub.flr_id IS NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'author') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('items').' AS itm
			LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id
			WHERE sub.mbr_id = ? AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_author = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $id))->row()->count;
		}
		if($type == 'category') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('items').' AS itm
			LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id
			WHERE sub.mbr_id = ? AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $id))->row()->count;
		}
	}
}
