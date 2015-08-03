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

if( ! function_exists('build_table_progression')) {
	function build_table_progression($title, $data, $legend) {
		$data = array_reverse($data);
		$legend = array_reverse($legend);

		$content = '<div class="mdl-card mdl-cell mdl-cell--3-col mdl-cell--12-col-phone mdl-cell--12-col-tablet">';
		$content .= '<div class="mdl-card__title"><h1 class="mdl-card__title-text">'.$title.'</h1></div>';
		$content .= '<div class="mdl-card__supporting-text mdl-color-text--grey">';

		if(count($data) > 0) {
			$total = max(array_values($data));
		} else {
			$total = 0;
		}

		$total_resume = 0;
		$lines = array();
		$prev = FALSE;
		foreach($legend as $k => $v) {
			$total_resume += $data[$k];
			if($total > 0) {
				$percent = ($data[$k] * 100) / $total;
			} else {
				$percent = 0;
			}
			if($prev) {
				$progression = round($data[$k] - $prev, 1);
			} else {
				$progression = NULL;
			}
			$prev = $data[$k];

			$lines[] = array($legend[$k], $data[$k], $progression, round($percent, 1));
		}
		$lines = array_reverse($lines);

		$content .= '<table>';
		foreach($lines as $line) {
			$content .= '<tr>';
			$content .= '<td>'.$line[0].'</td>';
			$content .= '<td class="result">'.$line[1].'</td>';
			if(is_null($line[2])) {
				$content .= '<td>&nbsp;</td>';
			} else if($line[2] == 0) {
				$content .= '<td class="result">=</td>';
			} else if($line[2] > 0) {
				$content .= '<td class="result">+'.$line[2].'</td>';
			} else if($line[2] < 0) {
				$content .= '<td class="result">'.$line[2].'</td>';
			}
			$content .= '<td style="width:50px;"><span class="color color_percent" style="width:'.$line[3].'%;">&nbsp;</span></td>';
			$content .= '</tr>';
		}
		$total_lines = count($lines);
		$content .= '<tr>';
		$content .= '<td>Total on '.$total_lines.'</td>';
		$content .= '<td class="result"><strong>'.$total_resume.'</strong></td>';
		$content .= '<td>&nbsp;</td>';
		$content .= '<td>&nbsp;</td>';
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '</div></div>';

		return $content;
	}
}

if( ! function_exists('build_table_repartition')) {
	function build_table_repartition($title, $data, $legend) {
		$content = '<div class="mdl-card mdl-cell mdl-cell--3-col mdl-cell--12-col-phone mdl-cell--12-col-tablet">';
		$content .= '<div class="mdl-card__title"><h1 class="mdl-card__title-text">'.$title.'</h1></div>';
		$content .= '<div class="mdl-card__supporting-text mdl-color-text--grey">';

		$total = array_sum($data);

		$total_resume = 0;
		$percent_resume = 0;
		$lines = array();
		foreach($legend as $k => $v) {
			$total_resume += $data[$k];
			if($total > 0) {
				$percent = ($data[$k] * 100) / $total;
			} else {
				$percent = 0;
			}
			$percent_resume += $percent;

			$lines[] = array($legend[$k], $data[$k], round($percent, 1));
		}

		$content .= '<table>';
		foreach($lines as $line) {
			$content .= '<tr>';
			$content .= '<td>'.$line[0].'</td>';
			$content .= '<td class="result">'.$line[1].'</td>';
			$content .= '<td class="result">'.$line[2].'%</td>';
			$content .= '<td style="width:50px;"><span class="color color_percent" style="width:'.$line[2].'%;">&nbsp;</span></td>';
			$content .= '</tr>';
		}
		$total_lines = count($lines);
		$content .= '<tr>';
		$content .= '<td>Total on '.$total_lines.'</td>';
		$content .= '<td class="result"><strong>'.$total_resume.'</strong></td>';
		$content .= '<td class="result"><strong>'.round($percent_resume, 1).'%</strong></td>';
		$content .= '<td>&nbsp;</td>';
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '</div></div>';

		return $content;
	}
}

if( ! function_exists('haversineGreatCircleDistance')) {
	function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		$distance = $angle * $earthRadius;
		if($distance < 1000) {
			$distance = round($distance);
			$distance .= ' m.';
		} else {
			$distance = round($distance/1000);
			$distance .= ' km.';
		}
		return $distance;
	}
}
