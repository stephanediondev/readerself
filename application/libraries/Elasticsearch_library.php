<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elasticsearch_library {
	public function __construct() {
		$this->CI =& get_instance();
	}
	function _action($action, $path, $body = false) {
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $this->CI->config->item('elasticsearch/url').$path);
		curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $action);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		if($body) {
			//echo '<textarea style="width:100%;height:100px;">'.json_encode($body).'</textarea><br>';
			curl_setopt($ci, CURLOPT_POSTFIELDS, json_encode($body));
		}
		return json_decode(curl_exec($ci));
	}
	function delete($path, $body = false) {
		return $this->_action('DELETE', $path, $body);
	}
	function get($path, $body = false) {
		return $this->_action('GET', $path, $body);
	}
	function post($path, $body = false) {
		return $this->_action('POST', $path, $body);
	}
	function put($path, $body = false) {
		return $this->_action('PUT', $path, $body);
	}
}
