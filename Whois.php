<?php

namespace Livetodot;

class Whois {

	 // The root whois server to look up TLD servers from...
	const ROOT_WHOIS_SERVER = 'whois.iana.org';

	 // The domain this class is handling
	protected $_domain;
	 // Once deturmined, this is the domain whois server...
	protected $_whoisServer;

	 // Constructor. Optionally builds with a domain...
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
	
		exec('whois -h '.self::ROOT_WHOIS_SERVER.' '.$domain, $output, $return);
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
