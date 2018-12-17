<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['config']= "./config/config.json";

$clients = new clients();
