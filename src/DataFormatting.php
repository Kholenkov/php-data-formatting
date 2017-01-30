<?php

namespace Kholenkov;

class DataFormatting {

	public static function intToWords($int, $names = null) {
		$result = [];
		$int = is_scalar($int) ? (string) $int : '';
		if (!is_array($names) || !isset($names[0]) || !is_string($names[0]) || !isset($names[1]) || !is_string($names[1]) || !isset($names[2]) || !is_string($names[2])) {
			$names = null;
		}
		if (strlen($int) && !preg_match('/[^0-9]/', $int)) {
			$select_name = function($number, $names) {
				return $names[(((int) $number % 100 > 4) && ((int) $number % 100 < 20)) ? 2 : [2, 0, 1, 1, 1, 2][min((int) $number % 10, 5)]];
			};
			$name = null;
			$zero = 'ноль';
			if ($int === '0') {
				$result[] = $zero;
			} else {
				$from_0_to_2 = [$zero, 'одна', 'две'];
				$from_0_to_19 = [
					$zero, 'один', 'два', 'три', 'четыре',
					'пять', 'шесть', 'семь', 'восемь', 'девять',
					'десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать',
					'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'
				];
				$tens = [
					'десять', 'двадцать', 'тридцать', 'сорок', 'пятьдесят',
					'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'
				];
				$hundreds = [
					'сто', 'двести', 'триста', 'четыреста', 'пятьсот',
					'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'
				];
				$thousands = [
					['тысяча', 'тысячи', 'тысяч'],
					['миллион', 'миллиона', 'миллионов'],
					['миллиард', 'миллиарда', 'миллиардов'],
					['триллион', 'триллиона', 'триллионов'],
					['квадриллион', 'квадриллиона', 'квадриллионов'],
					['квинтиллион', 'квинтиллиона', 'квинтиллионов'],
					['секстиллион', 'секстиллиона', 'секстиллионов'],
					['септиллион', 'септиллиона', 'септиллионов'],
					['октиллион', 'октиллиона', 'октиллионов'],
					['нониллион', 'нониллиона', 'нониллионов'],
					['дециллион', 'дециллиона', 'дециллионов']
				];
				$number_parts = preg_split('/(?=(\d{3})+(?!\d))/', $int, -1, PREG_SPLIT_NO_EMPTY);
				$i = count($number_parts) - 1;
				foreach ($number_parts as $j => $number_part) {
					if ($number_part = (int) $number_part) {
						$number_part_result = [];
						if ($hundred = floor($number_part / 100)) {
							$number_part_result[] = $hundreds[$hundred - 1];
							$number_part -= $hundred * 100;
						}
						if ($number_part) {
							if ($number_part > 19) {
								$ten = floor($number_part / 10);
								$number_part_result[] = $tens[$ten - 1];
								$number_part -= $ten * 10;
							}
							if ($number_part) {
								if (($i === 1) && in_array($number_part, [1, 2])) {
									$number_part_result[] = $from_0_to_2[$number_part];
								} else {
									$number_part_result[] = $from_0_to_19[$number_part];
								}
							}
						}
						if (isset($thousands[$i - 1])) {
							$number_part_result[] = $select_name($number_parts[$j], $thousands[$i - 1]);
						} elseif ($i !== 0) {
							$number_part_result[] = '{неизвестно}';
						} elseif ($names) {
							$name = $select_name($number_parts[$j], $names);
						}
						$result[] = implode(' ', $number_part_result);
					}
					$i--;
				}
				if (!$result) {
					$result[] = $zero;
				}
			}
			if (!$name && $names) {
				$name = $select_name(0, $names);
			}
			if ($name) {
				$result[] = $name;
			}
		}
		return implode(' ', $result);
	}

}
