# My Big ORM

![TheKairi78, french youtuber staring at a computer](img/tk_orm.png?raw=true)

## How to use My Big ORM

Setup everything in your project. Require the `MBO` namespace by using `use` keyword

## How to use it

Once everything has been setup, follow guidelines

* Create a file (here, we will take the exemple of a `User`)

```php
TODO
```

* Once everything has been pushed to database, require your `User.php` file. Like `MBO` by using `use`

* Then, call it and setup everything. Exemple down there:

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
```