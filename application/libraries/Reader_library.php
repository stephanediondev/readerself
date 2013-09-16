<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reader_library {
	public function __construct($params = array()) {
		set_error_handler(array($this, 'error_handler'));
		$this->CI =& get_instance();
		$this->errors = array();
		if(function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
		}
	}
	function error_handler($e_type, $e_message, $e_file, $e_line) {
		$e_type_values = array(1=>'E_ERROR', 2=>'E_WARNING', 4=>'E_PARSE', 8=>'E_NOTICE', 16=>'E_CORE_ERROR', 32=>'E_CORE_WARNING', 64=>'E_COMPILE_ERROR', 128=>'E_COMPILE_WARNING', 256=>'E_USER_ERROR', 512=>'E_USER_WARNING', 1024=>'E_USER_NOTICE', 2048=>'E_STRICT', 4096=>'E_RECOVERABLE_ERROR', 8192=>'E_DEPRECATED', 16384=>'E_USER_DEPRECATED', 30719=>'E_ALL');
		if(isset($e_type_values[$e_type]) == 1) {
			$e_type = $e_type_values[$e_type];
		}
		$value = $e_type.' | '.$e_message.' | '.$e_file.' | '.$e_line;
		$key = md5($value);
		$this->errors[$key] = $value;
	}
	function set_salt_password($mbr_password) {
		return sha1($mbr_password.$this->CI->config->item('salt_password'));
	}
	function set_template($template) {
		$this->template = $template;
	}
	function set_content_type($content_type) {
		$this->content_type = $content_type;
	}
	function set_charset($charset) {
		$this->charset = $charset;
	}
	function set_content($content) {
		$this->content = $content;
	}
	function get_debug() {
		if($this->content_type == 'application/json') {
			$debug = array();
			$debug['date'] = date('Y-m-d H:i:s');
			$debug['elapsed_time'] = $this->CI->benchmark->elapsed_time();
			if(function_exists('memory_get_peak_usage')) {
				$debug['memory_get_peak_usage'] = number_format(memory_get_peak_usage(), 0, '.', ' ');
			}
			if(function_exists('memory_get_usage')) {
				$debug['memory_get_usage'] = number_format(memory_get_usage(), 0, '.', ' ');
			}
			$key = 'errors ('.count($this->errors).')'; 
			$debug[$key] = array();
			foreach($this->errors as $error) {
				$debug[$key][] = $error; 
			}
			$key = 'queries ('.count($this->CI->db->queries).')';
			$debug[$key] = array();
			$u = 0;
			foreach($this->CI->db->queries as $k => $query) {
				$query_time = number_format($this->CI->db->query_times[$k], 20, '.', '');
				$debug[$key][$u] = array();
				$debug[$key][$u]['query'] = $query;
				$debug[$key][$u]['time'] = $query_time;
				$u++;
			}
		}
		if($this->content_type == 'text/plain' || $this->content_type == 'text/html') {
			$debug = "\n";
			if($this->content_type == 'text/html') {
				$debug .= '<!--'."\n";
			}
			$debug .= '##################################'."\n";
			$debug .= 'debug'."\n";
			$debug .= '##################################'."\n";
			$debug .= 'date: '.date('Y-m-d H:i:s')."\n";
			$debug .= 'elapsed_time: '.$this->CI->benchmark->elapsed_time()."\n";
			if(function_exists('memory_get_peak_usage')) {
				$debug .= 'memory_get_peak_usage: '.number_format(memory_get_peak_usage(), 0, '.', ' ')."\n";
			}
			if(function_exists('memory_get_usage')) {
				$debug .= 'memory_get_usage: '.number_format(memory_get_usage(), 0, '.', ' ')."\n";
			}
			$debug .= '##################################'."\n";
			$debug .= 'errors ('.count($this->errors).')'."\n";
			foreach($this->errors as $error) {
				$debug .= $error."\n";
			}
			$debug .= '##################################'."\n";
			$debug .= 'queries ('.count($this->CI->db->queries).')'."\n";
			foreach($this->CI->db->queries as $k => $query) {
				$debug .= '###'."\n";
				$query_time = number_format($this->CI->db->query_times[$k], 20, '.', '');
				$debug .= $query."\n";
				$debug .= $query_time."\n";
			}
			$debug .= '##################################'."\n";
			if($this->content_type == 'text/html') {
				$debug .= '-->'."\n\n";
			}
		}
		return $debug;
	}
	function build_filters($filters) {
		$flt = array();
		$flt[] = '1';
		foreach($filters as $k =>$v) {
			if(isset($_SESSION[$k]) == 0) {
				$_SESSION[$k] = '';
			}
			$value = '';
			if($this->CI->input->post($k) || isset($_POST[$k]) == 1) {
				$value = strval($this->CI->input->post($k));
				$this->CI->session->set_userdata($k, strval($this->CI->input->post($k)));
			} elseif($this->CI->session->userdata($k) != '') {
				$value = $this->CI->session->userdata($k);
			}
			if($value != '') {
				if($v[1] == 'compare_today') {
					if($value == 1) {
						$flt[] = $v[0].' <= '.$this->CI->db->escape(date('Y-m-d H:i:s'));
					}
					if($value == 0) {
						$flt[] = $v[0].' > '.$this->CI->db->escape(date('Y-m-d H:i:s'));
					}
				}
				if($v[1] == 'compare_field') {
					if($value == 1) {
						$flt[] = $v[0].' <= '.$v[2];
					}
					if($value == 0) {
						$flt[] = $v[0].' > '.$v[2];
					}
				}
				if($v[1] == 'null') {
					if($value == 1) {
						$flt[] = $v[0].' IS NULL';
					}
					if($value == 0) {
						$flt[] = $v[0].' IS NOT NULL';
					}
				}
				if($v[1] == 'notnull') {
					if($value == 1) {
						$flt[] = $v[0].' IS NOT NULL';
					}
					if($value == 0) {
						$flt[] = $v[0].' IS NULL';
					}
				}
				if($v[1] == 'inferior') {
					$flt[] = $v[0].' <= '.$this->CI->db->escape($value);
				}
				if($v[1] == 'superior') {
					$flt[] = $v[0].' >= '.$this->CI->db->escape($value);
				}
				if($v[1] == 'inferior_date') {
					$flt[] = $v[0].' <= '.$this->CI->db->escape($value.' 23:59:59');
				}
				if($v[1] == 'superior_date') {
					$flt[] = $v[0].' >= '.$this->CI->db->escape($value.' 00:00:00');
				}
				if($v[1] == 'equal') {
					$flt[] = $v[0].' = '.$this->CI->db->escape($value);
				}
				if($v[1] == 'like') {
					$flt[] = $v[0].' LIKE '.$this->CI->db->escape('%'.$value.'%');
				}
			}
		}
		return $flt;
	}
	function build_pagination($total, $per_page, $ref = 'default') {
		$this->CI->load->library('pagination');

		$config = array();
		$config['base_url'] = '?';
		$config['num_links'] = 5;
		$config['total_rows'] = $total;
		$config['per_page'] = $per_page;
		$config['page_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$config['query_string_segment'] = $ref.'_pg';
		$config['first_url'] = '?'.$config['query_string_segment'].'=1';

		$pages = ceil($total/$config['per_page']);

		$key = 'per_page_'.$config['query_string_segment'];
		if($this->CI->input->get($config['query_string_segment']) && is_numeric($this->CI->input->get($config['query_string_segment']))) {
			$page = $this->CI->input->get($config['query_string_segment']);
			$this->CI->session->set_userdata($key, $page);
		} else if($this->CI->session->userdata($key) && is_numeric($this->CI->session->userdata($key))) {
			$_GET[$config['query_string_segment']] = $this->CI->session->userdata($key);
		} else {
			$_GET[$config['query_string_segment']] = 0;
		}
		$start = ($this->CI->input->get($config['query_string_segment']) * $config['per_page']) - $config['per_page'];
		if($start < 0 || $this->CI->input->get($config['query_string_segment']) > $pages) {
			$start = 0;
			$_GET[$config['query_string_segment']] = 1;
		}

		if($pages == 1) {
			$position = $total;
		} else if($_GET[$config['query_string_segment']] == $pages && $pages != 0) {
			$position = ($start+1).'-'.$total.'/'.$total;
		} else if($pages != 0) {
			$position = ($start+1).'-'.($start+$config['per_page']).'/'.$total;
		} else {
			$position = $total;
		}

		$this->CI->pagination->initialize($config);
		return array('output'=>$this->CI->pagination->create_links(), 'start'=>$start, 'limit'=>$config['per_page'], 'position'=>$position);
	}
	function crawl_items($fed_id, $items) {
		foreach($items as $sp_item) {
			$query = $this->CI->db->query('SELECT * FROM '.$this->CI->db->dbprefix('items').' AS itm WHERE itm.itm_link = ? GROUP BY itm.itm_id', array($sp_item->get_link()));
			if($query->num_rows() == 0) {
				$this->CI->db->set('fed_id', $fed_id);

				if($sp_item->get_title()) {
					$this->CI->db->set('itm_title', $sp_item->get_title());
				} else {
					$this->CI->db->set('itm_title', '-');
				}

				if($author = $sp_item->get_author()) {
					$this->CI->db->set('itm_author', $author->get_name());
				}

				$this->CI->db->set('itm_link', $sp_item->get_link());

				if($sp_item->get_content()) {
					$this->CI->db->set('itm_content', $sp_item->get_content());
				} else {
					$this->CI->db->set('itm_content', '-');
				}

				if($sp_item->get_latitude() && $sp_item->get_longitude()) {
					$this->CI->db->set('itm_latitude', $sp_item->get_latitude());
					$this->CI->db->set('itm_longitude', $sp_item->get_longitude());
				}

				$sp_itm_date = $sp_item->get_gmdate('Y-m-d H:i:s');
				if($sp_itm_date) {
					$this->CI->db->set('itm_date', $sp_itm_date);
				} else {
					$this->CI->db->set('itm_date', date('Y-m-d H:i:s'));
				}

				$this->CI->db->set('itm_datecreated', date('Y-m-d H:i:s'));

				$this->CI->db->insert('items');

				$itm_id = $this->CI->db->insert_id();

				$enclosures = array();

				foreach($sp_item->get_iframes() as $iframe) {
					if($iframe['src'] && $iframe['width'] && $iframe['height']) {
						if(stristr($iframe['src'], 'vimeo.com') || stristr($iframe['src'], 'youtube.com') || stristr($iframe['src'], 'dailymotion.com')) {
							if(!in_array($iframe['src'], $enclosures)) {
								$this->CI->db->set('itm_id', $itm_id);
								$this->CI->db->set('enr_link', $iframe['src']);
								if(stristr($iframe['src'], 'vimeo.com')) {
									$this->CI->db->set('enr_type', 'video/vimeo');
								}
								if(stristr($iframe['src'], 'youtube.com')) {
									$this->CI->db->set('enr_type', 'video/youtube');
								}
								if(stristr($iframe['src'], 'dailymotion.com')) {
									$this->CI->db->set('enr_type', 'video/dailymotion');
								}
								$this->CI->db->set('enr_width', $iframe['width']);
								$this->CI->db->set('enr_height', $iframe['height']);
								$this->CI->db->set('enr_datecreated', date('Y-m-d H:i:s'));
								$this->CI->db->insert('enclosures');
								$enclosures[] = $iframe['src'];
							}
						}
					}
				}

				foreach($sp_item->get_categories() as $category) {
					if($category->get_label()) {
						if(strstr($category->get_label(), ',')) {
							$categories = explode(',', $category->get_label());
							foreach($categories as $category_split) {
								$category_split = trim( strip_tags( html_entity_decode( $category_split ) ) );
								$this->CI->db->set('itm_id', $itm_id);
								$this->CI->db->set('cat_title', $category_split);
								$this->CI->db->set('cat_datecreated', date('Y-m-d H:i:s'));
								$this->CI->db->insert('categories');
							}
						} else {
							$this->CI->db->set('itm_id', $itm_id);
							$this->CI->db->set('cat_title', trim( strip_tags( html_entity_decode( $category->get_label() ) ) ) );
							$this->CI->db->set('cat_datecreated', date('Y-m-d H:i:s'));
							$this->CI->db->insert('categories');
						}
					}
				}

				foreach($sp_item->get_enclosures() as $enclosure) {
					if($enclosure->get_link() && $enclosure->get_type() && $enclosure->get_length()) {
						$link = $enclosure->get_link();
						if(substr($link, -2) == '?#') {
							$link = substr($link, 0, -2);
						}
						if(!in_array($link, $enclosures)) {
							$this->CI->db->set('itm_id', $itm_id);
							$this->CI->db->set('enr_link', $link);
							$this->CI->db->set('enr_type', $enclosure->get_type());
							$this->CI->db->set('enr_length', $enclosure->get_length());
							$this->CI->db->set('enr_width', $enclosure->get_width());
							$this->CI->db->set('enr_height', $enclosure->get_height());
							$this->CI->db->set('enr_datecreated', date('Y-m-d H:i:s'));
							$this->CI->db->insert('enclosures');
							$enclosures[] = $link;
						}
					}
				}
			} else {
				break;
			}
			unset($sp_item);
		}
	}
	function prepare_content($content) {
		//$content = strip_tags($content, '<dt><dd><dl><table><caption><tr><th><td><tbody><thead><h2><h3><h4><h5><h6><strong><em><code><pre><blockquote><p><ul><li><ol><br><del><a><img><figure><figcaption><cite><time><abbr>');

		preg_match_all('/<a[^>]+>/i', $content, $result);
		foreach($result[0] as $flr_a) {
			if(!preg_match('/(target)=("[^"]*")/i', $flr_a, $result)) {
				$content = str_replace($flr_a, str_replace('<a', '<a target="_blank"', $flr_a), $content);
			}
		}

		preg_match_all('/<img[^>]+>/i', $content, $result);
		foreach($result[0] as $flr_img) {
			$attribute_src = false;
			if(preg_match('/(src)=("[^"]*")/i', $flr_img, $result)) {
				$attribute_src = str_replace('"', '', $result[2]);
			}

			$attribute_width = false;
			if(preg_match('/(width)=("[^"]*")/i', $flr_img, $result)) {
				$attribute_width = str_replace('"', '', $result[2]);
			}

			$attribute_height = false;
			if(preg_match('/(height)=("[^"]*")/i', $flr_img, $result)) {
				$attribute_height = str_replace('"', '', $result[2]);
			}

			if($attribute_width == 1 || $attribute_height == 1 || stristr($attribute_src, 'feedsportal.com') || stristr($attribute_src, 'feedburner.com')) {
				$content = str_replace($flr_img, '', $content);
			}
		}
		return $content;
	}
}
