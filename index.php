<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['MBO'] = json_decode(file_get_contents('./config/config.json'), true);

$clients = new clients();
//$clients->INSERT(["lastname", "Max"], ["firstname", "ime"], ["address", "adres"], ["city", "citi"], ["country_id", 42], ["phone", "0102030405"], ["email", "e@ma.il"]);
//$clients->UPDATE(["lastname", "Maxx"], ["qdtht", "qfhdqfs"], ["address", "slt"]);
//$clients->buildQuery()->execute();
//$clients->SELECT("city", "phone");
//$clients->WHERE(["id", "=", 29]);
//$clients->DELETE(false);
//$clients->COUNT("*");

//$clients = $clients->getById(2);
//var_dump($clients);
//$clients->deleteEntity();
//$clients->buildQuery();
//var_dump($clients->execute());

