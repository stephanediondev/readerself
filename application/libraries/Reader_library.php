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
}
