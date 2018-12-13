<?php

require_once("vendor/autoload.php");

use MBO\DBManager;

global $configPath;
$configPath = "./config/config.json";

$a = new DBManager($configPath);