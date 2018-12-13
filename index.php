<?php

require_once "vendor/autoload.php";

use MBO\DBManager;
use MBO\MBOBuilder;

global $configPath;
$configPath = "config/config.json";

$a = new DBManager($configPath);

$b = new MBOBuilder();
$b->SELECT("id", "firstname");
