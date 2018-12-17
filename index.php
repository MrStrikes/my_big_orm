<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['config'] = "./config/config.json";

$clients = new clients();
$clients->SELECT('id', 'lastname', 'firstname', 'address', 'city');
var_dump($clients->buildQuery());
