<?php

require_once('../Whois.php');

$whois = new Livetodot\Whois();
//var_dump($whois);

var_dump($whois->findServer('com'));