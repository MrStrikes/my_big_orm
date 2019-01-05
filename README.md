# My Big ORM

![TheKairi78, french youtuber staring at a computer](img/tk_orm.png?raw=true)

## Installation

Pour utiliser correctement MBO il faut définir dans `GLOBAL['MBO']` la configutation. Pour en savoir plus sur la configuration cliquez [ici](https://github.com/MrStrikes/my_big_orm/blob/master/config/config.json.dist)

## Utilisation

Créer un fichier pour votre entité. celui-ci doit `extends MBOEntity`.  

Attention `MBOEntity` implemente[`EntityInterface`.php](https://github.com/MrStrikes/my_big_orm/blob/master/src/MBO/EntityInterface.php)  
* `getCol()` retourne un tableau des noms de colonne dans votre DB. Chaque nom dans le tableau correspond à une variable dans votre entité, chacune de ces variable doit posséder un getter et un setter en [camelCase](https://fr.wikipedia.org/wiki/Camel_case) (ex: `public function getCountryId(){}`)
* `getTableName()` retourne le nom de votre tableau dans votre DB.
* `getId()` retourne l'id de votre entité  

Vous pouvez maintenant utiliser MBO sur votre entité.

### Utilisation simple

```php
$client = new clients(2);
```
récupère le client avec l'id 2 dans votre DB.
```php
$client = new clients();
```
récupère une instance de client vide.
```php
$data = $client->getData();
```
récupère les data du client dans un tableau
```php
$client = $client->buildEntity(array $data, bool $newEntity)
```
crée une nouvelle entité ou complète l'entité actuelle selon `$newentity` avec les données de `$data`
```php
$client->getById($id, bool $newEntity);
```
récupère les data de l'entité dans la DB selon l'id et appel `buildEntity()` avec les data récupéré.
```php
$clients = $client->getAll();
```
return un tableau d'entité contenant toutes les entités de la db.
```php
$clients = $client->getByCriteria(...$where);
```
return un tableau d'entité en fonction des parametres where envoyé (voir l'utilisation de WHERE dans l'utilisation avancée).
```php
$client->save();
```
Enregistre l'entité `$client` dans la db, si l'id de `$client` existe dans la db, alors cette ligne sera mise à jour, sinon on crée une nouvelle ligne.  
```php
$client->deleteEntity();
```
Supprime dans la db le ligne avec l'id de `$client`.
```php
$client->exist(...where);
```
retourne un booléen en fonction de ce qu'il trouve dans la db avec les parametres where (voir l'utilisation de WHERE dans l'utilisation avancée).
```php
$client->countEntity();
```
Compte le nombre d'entité dans la DB.

### Utilisation avancée

MBO vous permet de faire aussi vos propre requete.

SELECT + COUNT + ORDER BY + WHERE:
```php
<?php
    $client = new clients();
    $client->SELECT('firstname', 'lastname') // SELECT('*') est aussi possible
        ->COUNT('lastname', true) // true = DISTINCT lastname, FALSE = lastname, false par default
        ->ORDERBY(['id', 'ASC'], ['phone', 'DESC']) 
        ->WHERE(['id', '>', '0'], ['id', '<', '10'], ['id', '=', 42, 'OR'])
        ->buildQuery();
    $result = $client->execute(2, false);  //execute(fetchmode = 2, clear = true)
    $client->clear();
?>
```
`SELECT()` prend en parametre des nom de colonne.  
`COUNT()` prend en parametre un nom de colonne et un booléen pour présicer le DISTINCT ou non.
`ORDERBY()` prend en parametre des tableaux avec un nom colonne et un suffixe qui par default vaut ASC.  
`WHERE()` prend un parametre des tableaux avec un nom de colonne, la condition a tester, une valeur, et un opérateur qui par default vaut AND. Les where seront placé dans leur ordre d'appel, l'opérateur est placer avant le  contenue du tableau dans notre exemple, le where vaut : `id > 0 AND id < 10 OR id = 42`.  
`buildQuery()` construit la query et `execute()` l'execute et retourne le résultat dans un tableau.
La fonction `clear()` vide les parametres select,update,where,insert,delete,orderby,count. par default la fonction execute appel cette fonction après son execution.  

```php
<?php
$client = new clients();
$client->INSERT(["lastname", "value"], ["firstname", "value"], ["address", "value"], ["city", "value"], ["country_id", "value"], ["phone", "value"], ["email", "value"])
    ->buimdQuery()->execute();
?>
```
`INSERT()` prend un parametre des tableaux des valeurs à insérer: `["nom de la colonne", "valeur"]`.  

```php
<?php
$client = new client();
$client->UPDATE(["lastname", "newValue"], ["address", "newValue"])
    ->buildQuery()->execute();
?>
```
`UPDATE()` prend en parametre des tableaux des valeurs à modifier: `["nomdelacolonne", "nouvelle valeur"]`.
```php
$client = new client();
$client->DELETE(true)
    ->WHERE(["id", "=", 78])
    ->buildQuery()->execute();
```
`DELETE()` prend en parametre un booléen pour définir si on doit supprimer on non les lignes récupérer par le where.



Vous pourrez trouver un exemple d'entité [ici](https://github.com/MrStrikes/my_big_orm/blob/master/src/test/clients.php) avec le [tableau correspondant dans la db](https://github.com/MrStrikes/my_big_orm/blob/master/src/test/clients.sql).
Et voici [un fichier](https://github.com/MrStrikes/my_big_orm/blob/master/index.php) qui utilise quelques fonctionnalitées de l'entité d'exemple.



```php
<?php

use User;

$id = 42;
$user = new User($id); // get user with id 42
$user->firtstname = 'Maxime';
$user->save();

$newUser = new User();
$newUser->firstname = 'Wassim';
$newUser->lastname = 'Tacos';
$newUser->country_id = 1;
$newUser->save();

$userToDelete = new User(42);
$userToDelete->delete();

$selectUsers = new MBOQuery('user');
$selectUsers->select('id');
$selectUsers->where('firstname = Maxime');
$selectUsers->orderBy('id');
$users = $selectUsers->execute();
```
