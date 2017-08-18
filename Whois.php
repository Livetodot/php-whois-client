<?php

namespace Livetodot;

class Whois {

	 // The root whois server to look up TLD servers from...
	const ROOT_WHOIS_SERVER = 'whois.iana.org';

	 // The domain this class is handling
	protected $_domain;
	 // Once deturmined, this is the domain whois server...
	protected $_whoisServer;

	 // Constructor. Optionally builds with a domain. If instantiated the class acts as a cache
	 // for whois data. Further requests for data will not go back to the source unless forced.
	public function __construct($domain = null, $quick = false) {

		if (null !== $domain) {
			$this->_domain = $domain;
			if (false == $quick) {
				$whoisServer = self::findServer($this->_domain);
				if (false !== $whoisServer) {
					$this->_whoisServer = $whoisServer;
				}
			}
		}
		
	}

	/* Static methods. */
	
	 // Returns the whois server to query for this TLD...
	public static function findServer($domain) {
	
		$whoisResult = self::whoisLookup($domain, self::ROOT_WHOIS_SERVER, true);
		if (false !== $whoisResult && is_array($whoisResult)) {
			foreach ($whoisResult AS $whoisLine) {
				$outputLineElements = preg_split('/: +/', trim($whoisLine), 2);
				if ('whois' == $outputLineElements[0]) {
					return $outputLineElements[1];
				}
			}
		}
		
		return false;
		
	}
	
	 // Carries out a whois lookup for the given domain on the optional given whois server.
	 // Returns either the raw output (trimmed lines as an array) or a parsed version.
	public static function whoisLookup($domain, $whoisServer = null, $raw = false) {
	
		if (null == $whoisServer) {
			$whoisServer = self::findServer($domain);
		}
		
		exec('whois -h '.$whoisServer.' '.$domain, $output, $return);
		if ((0 == $return) && is_array($output)) {
			$whoisData = array();
			foreach ($output AS $outputLine) {
				if (!empty($outputLine)) {
					$whoisData[] = trim($outputLine);
				}
			}
			return $whoisData;
		}
		
		return false;
	
	}
	
}
