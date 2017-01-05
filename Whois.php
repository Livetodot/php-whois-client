<?php

namespace Livetodot;

class Whois {

	 // Returns the whois server to query for this TLD...
	public function findServer($domain) {
	
		exec('whois -h whois.iana.org '.$domain, $output, $return);
		if ((0 == $return) && is_array($output)) {
			$whoisData = array();
			foreach ($output AS $outputLine) {
				if (!empty($outputLine)) {
					$outputLineElements = preg_split('/: +/', trim($outputLine), 2);
					if ('whois' == $outputLineElements[0]) {
						return trim($outputLineElements[1]);
					}
				}
			}
		}
		
		return false;
		
	}
	
}
