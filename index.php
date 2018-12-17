<?php

require_once "vendor/autoload.php";

use MBO\DBManager;
use test\clients;

global $configPath;
$configPath = "./config/config.json";

$a = new DBManager($configPath);

$b = new clients();
$b->SELECT();
