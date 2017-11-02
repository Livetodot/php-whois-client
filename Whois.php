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
	
		$whoisResult = self::whoisLookup($domain, false, self::ROOT_WHOIS_SERVER, true);
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
	public static function whoisLookup($domain, $extended = false, $whoisServer = null, $raw = false) {
	
		// Work out which whois server we're going to talk to...
		if (null == $whoisServer) {
			$whoisServer = self::findServer($domain);
		}
		
		// Include whois parsing bits...
		require_once(dirname(__FILE__).'/Whois_Parser.php');
		require_once(dirname(__FILE__).'/patterns/'.$whoisServer.'.php');

		// Carry out the actual whois lookup and tidy up the result a little...
		exec('whois -h '.$whoisServer.' -H '.$domain, $output, $return);
		if ((0 == $return) && is_array($output)) {
			$whoisData = array();
			foreach ($output AS $outputLine) {
				if (!empty($outputLine)) {
					$whoisData[] = trim($outputLine);
				}
			}
			
			// If we want extended data, we'll get that now...
			if (true === $extended) {
				$registrarWhois = Whois_Parser::getElement($whoisData, $parsePatterns['registarWhois']);
				if (false !== $registrarWhois) {
					$extendedWhoisResult = self::whoisLookup($domain, false, $registrarWhois, true);
					if (false !== $extendedWhoisResult && is_array($extendedWhoisResult)) {
						$whoisData = array_merge($whoisData, $extendedWhoisResult);
					}
				}
			}
			
			// Return the compiled data, either raw or parsed...
			if (true == $raw) {
				return $whoisData;
			} else {
				$parsedData = array();
				foreach ($parsePatterns AS $key => $pattern) {
					$parsedLine = Whois_Parser::getElement($whoisData, $pattern);
					if (false !== $parsedLine) {
						$parsedData[$key] = $parsedLine;
					}
				}
				return $parsedData;
			}
			
		}
		
		return false;
	
	}
	
}
