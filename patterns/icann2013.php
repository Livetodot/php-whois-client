<?php

// Parsing patterns for ICANN's 2013 Registrar Accreditation Agreement
// (https://www.icann.org/resources/pages/approved-with-specs-2013-09-17-en)

$parsePatterns = array('registrar' => '/Registrar: (.*)/',
                       'registrarWhois' => '/Registrar WHOIS Server: (.*)/',
                       'registrationDate' => '/Creation Date: (.*)/',
                       'expiryDate' => '/Registry Expiry Date: (.*)/');