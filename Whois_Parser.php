<?php

namespace Livetodot;

class Whois_Parser {

	public static function getElement(array $rawWhoisData, $pattern) {
	
		foreach ($rawWhoisData AS $whoisLine) {
			if (preg_match($pattern, $whoisLine, $matches)) {
				return $matches[1];
			}
		}
		
		return false;
	
	}

}