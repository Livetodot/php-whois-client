<?php

// Parsing patterns for whois.verisign-grs.com.

$parsePatterns = array('registarWhois' => '/Registrar WHOIS Server: (.*)/',
                       'registrationDate' => '/Creation Date: (.*)/',
											 'expiryDate' => '/Registry Expiry Date: (.*)/');