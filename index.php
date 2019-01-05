<?php

require_once "vendor/autoload.php";

use test\clients;

$GLOBALS['MBO'] = json_decode(file_get_contents('./config/config.json'), true);

//$client1 = new clients();
//$client1->setLastname('lastname');
//$client1->setFirstname('firstname');
//$client1->setAddress('address');
//$client1->setCity('city');
//$client1->setCountryId(1);
//$client1->setPhone('phone');
//$client1->setEmail('email');
//$client1->save();
//var_dump($client1->getData());


////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client2 = new clients($client1->getId()); // === $client2 = new clients(); === $client2->getById($client1->getId()); === $client2->getByCriteria(['id', '=', $client1->getId()]);
//$client2->setLastname('new lastname')
//    ->save();
//var_dump($client2->getData(), $client2->exist());

///////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client3 = new clients($client2->getId());
//$client3->deleteEntity();
//var_dump($client3->exist());

// comment $client1, $client2, $client3
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client4 = new clients();
//$clients = $client4->getAll();
//var_dump($clients, $client4->countEntity());

// comment $client4
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client5 = new clients();
//$client5->INSERT(["lastname", "value"], ["firstname", "value"], ["address", "value"], ["city", "value"], ["country_id", "1"], ["phone", "value"], ["email", "value"])
//    ->buildQuery()->execute();
// check your DB :)

//comment $client5
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client6 = new clients();
//$client6->UPDATE(["lastname", "newValue"], ["address", "newValue"])
//    ->WHERE(['lastname', '=', 'value'], ['firstname', '=', 'value'])
//    ->buildQuery()->execute();
// check your DB :)

//comment $client6
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client7 = new clients();
//$client7->SELECT('*')
//    ->WHERE(['lastname', '=', 'newValue'], ['id', '=', 5, 'OR'])
//    ->ORDERBY('id')
//    ->buildQuery();
//$result = $client7->execute();
//var_dump($client7->buildEntity($result[0], false), $result[1]);

// comment $client7
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$client8 = new clients();
//$client8->DELETE(true)
//    ->WHERE(['lastname', '=', 'newValue'], ['address', '=', 'newValue'], ['firstname', '=', 'value'])
//    ->buildQuery()->execute();
// check your DB :)


