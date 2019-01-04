<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['config'] = "./config/config.json";

$clients = new clients();
//$clients->INSERT(["lastname", "Max"], ["firstname", "ime"], ["address", "adres"], ["city", "citi"], ["country_id", 42], ["phone", "0102030405"], ["email", "e@ma.il"]);
//$clients->UPDATE(["lastname", "Maxx"], ["qdtht", "qfhdqfs"], ["address", "slt"]);
$clients->SELECT("city", "phone");
$clients->COUNT("lastname", true)
    ->COUNT("dfgsdfh")
    ->COUNT("firstname");
var_dump($clients->getCount());
$clients->buildQuery();
var_dump($clients->getQuery());
var_dump($clients->execute());
