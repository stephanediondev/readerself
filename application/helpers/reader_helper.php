<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if( ! function_exists('convert_to_ascii')) {
	function convert_to_ascii($url) {
		$parts = parse_url($url);
		if(!isset($parts['host'])) {
			return $url;
		}
		if(mb_detect_encoding($parts['host']) != 'ASCII') {
			$url = str_replace($parts['host'], idn_to_ascii($parts['host']), $url);
		}
		return $url;
	}
}

if( ! function_exists('generate_string')) {
	function generate_string($size=8, $with_numbers=true, $with_tiny_letters=true, $with_capital_letters=false) { 
		$string = '';
		$sizeof_lchar = 0;
		$letter = '';
		$letter_tiny = 'abcdefghijklmnopqrstuvwxyz';
		$letter_capital = 'ABCDEFGHIJKLMNOPRQSTUVWXYZ';
		$letter_number = '0123456789';
		if($with_tiny_letters == true) {
			$sizeof_lchar += 26;
			if(isset($letter) == 1) {
				$letter .= $letter_tiny;
			} else {
				$letter = $letter_tiny;
			}
		}
		if($with_capital_letters == true) {
			$sizeof_lchar += 26;
			if(isset($letter) == 1) {
				$letter .= $letter_capital;
			} else {
				$letter = $letter_capital;
			}
		}
		if($with_numbers == true) {
			$sizeof_lchar += 10;
			if(isset($letter) == 1) {
				$letter .= $letter_number;
			} else {
				$letter = $letter_number;
			}
		}
		if($sizeof_lchar > 0) {
			//srand((double)microtime()*date('YmdGis'));
			for($cnt = 0; $cnt < $size; $cnt++) {
				$char_select = rand(0, $sizeof_lchar - 1);
				$string .= $letter[$char_select];
			}
		}
		return $string;
	}
}
