<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['config'] = "./config/config.json";

$clients = new clients();
//$clients->INSERT(["lastname", "Max"], ["firstname", "ime"], ["address", "adres"], ["city", "citi"], ["country_id", 42], ["phone", "0102030405"], ["email", "e@ma.il"]);
//$clients->UPDATE(["lastname", "Maxx"], ["qdtht", "qfhdqfs"], ["address", "slt"]);
$clients->SELECT("*");
$clients->COUNT("lastname", true);
//$clients->DELETE(true);
//$clients->WHERE(['id', '<=', '3']);

//$clients->ORDERBY(["lastname", "DESC"]);
$clients->buildQuery();
var_dump($clients->getQuery());
$result = $clients->execute();
var_dump($result);