<?php

require_once "vendor/autoload.php";

use MBO\DBManager;
use test\clients;

global $configPath;
$configPath = "./config/config.json";

$a = new DBManager($configPath);

$b = new clients($configPath);
$b->SELECT('id', 'lastname', 'firstname', 'address');
$b->buildQuery();
