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
	function build_table_progression($title, $data, $legend, $suffix_data = FALSE, $total = TRUE) {
		$data = array_reverse($data);
		$legend = array_reverse($legend);

		$content = '<div class="data_table">';
		$content .= '<h3>'.$title.'</h3>';

		$total_resume = 0;
		$lines = array();
		$prev = FALSE;
		foreach($legend as $k => $v) {
			$total_resume += $data[$k];
			if($prev) {
				$progression = round($data[$k] - $prev, 1);
			} else {
				$progression = NULL;
			}
			$prev = $data[$k];

			$lines[] = array($legend[$k], $data[$k], $progression);
		}
		$lines = array_reverse($lines);

		$content .= '<table>';
		foreach($lines as $line) {
			$content .= '<tr>';
			$content .= '<td>'.$line[0].'</td>';
			if($suffix_data) {
				$content .= '<td class="result">'.$line[1].$suffix_data.'</td>';
			} else {
				$content .= '<td class="result">'.$line[1].'</td>';
			}
			if(is_null($line[2])) {
				$content .= '<td>&nbsp;</td>';
				$content .= '<td style="width:100px;">&nbsp;</td>';
			} else if($line[2] == 0) {
				$content .= '<td class="result">=</td>';
				$content .= '<td style="width:100px;"><span class="color color_5gray">&nbsp;</span></td>';
			} else if($line[2] > 0) {
				$content .= '<td class="result">+'.$line[2].'</td>';
				$content .= '<td style="width:100px;"><span class="color color_2green">&nbsp;</span></td>';
			} else if($line[2] < 0) {
				$content .= '<td class="result">'.$line[2].'</td>';
				$content .= '<td style="width:100px;"><span class="color color_1red">&nbsp;</span></td>';
			}
			$content .= '</tr>';
		}
		$total_lines = count($lines);
		if($total_lines < 12) {
			$reste = 12 - $total_lines;
			if($reste > 0) {
				for($i=0;$i<$reste;$i++) {
					$content .= '<tr>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '</tr>';
				}
			}
		}
		$content .= '<tr>';
		if($total) {
			$content .= '<td>Total sur '.$total_lines.'</td>';
			if($suffix_data) {
				$content .= '<td class="result"><strong>'.$total_resume.$suffix_data.'</strong></td>';
			} else {
				$content .= '<td class="result"><strong>'.$total_resume.'</strong></td>';
			}
			$content .= '<td>&nbsp;</td>';
			$content .= '<td>&nbsp;</td>';
		} else {
			$content .= '<td>&nbsp;</td>';
			$content .= '<td>&nbsp;</td>';
			$content .= '<td>&nbsp;</td>';
			$content .= '<td>&nbsp;</td>';
		}
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '</div>';

		return $content;
	}
}

if( ! function_exists('build_table_repartition')) {
	function build_table_repartition($title, $data, $legend, $suffix_data = FALSE) {
		$content = '<div class="data_table">';
		$content .= '<h3>'.$title.'</h3>';

		$total = array_sum($data);

		$total_resume = 0;
		$percent_resume = 0;
		$lines = array();
		foreach($legend as $k => $v) {
			$total_resume += $data[$k];
			$percent = ($data[$k] * 100) / $total;
			$percent_resume += $percent;

			$lines[] = array($legend[$k], $data[$k], round($percent, 1));
		}

		$content .= '<table>';
		foreach($lines as $line) {
			$content .= '<tr>';
			$content .= '<td>'.$line[0].'</td>';
			if($suffix_data) {
				$content .= '<td class="result">'.$line[1].$suffix_data.'</td>';
			} else {
				$content .= '<td class="result">'.$line[1].'</td>';
			}
			$content .= '<td class="result">'.$line[2].'%</td>';
			$content .= '<td style="width:100px;"><span class="color color_percent" style="width:'.$line[2].'%;">&nbsp;</span></td>';
			$content .= '</tr>';
		}
		$total_lines = count($lines);
		if($total_lines < 12) {
			$reste = 12 - $total_lines;
			if($reste > 0) {
				for($i=0;$i<$reste;$i++) {
					$content .= '<tr>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '<td>&nbsp;</td>';
					$content .= '</tr>';
				}
			}
		}
		$content .= '<tr>';
		$content .= '<td>Total sur '.$total_lines.'</td>';
		if($suffix_data) {
			$content .= '<td class="result"><strong>'.$total_resume.$suffix_data.'</strong></td>';
		} else {
			$content .= '<td class="result"><strong>'.$total_resume.'</strong></td>';
		}
		$content .= '<td class="result"><strong>'.round($percent_resume, 1).'%</strong></td>';
		$content .= '<td>&nbsp;</td>';
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '</div>';

		return $content;
	}
}
