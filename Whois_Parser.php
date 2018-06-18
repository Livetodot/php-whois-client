<?php

namespace Livetodot;

class Whois_Parser {

	public static function getElement(array $rawWhoisData, $pattern) {

		$elements = array();
		foreach ($rawWhoisData AS $whoisLine) {
			if (preg_match($pattern, $whoisLine, $matches)) {
				$elements[] = $matches[1];
			}
		}

		$numElements = count($elements);
		if (1 < $numElements) {
			return $elements;
		} else if (1 == $numElements) {
			return $elements[0];
		} else {
			return false;
		}

	}

}