<?php

namespace MBO;


class DBManager
{
    private $pdo;

    public function __construct()
    {

        $params = json_decode(file_get_contents($GLOBALS['config']), true);
        $address = $params['db']['host'];
        if ($params['db']['port']) {
            $address .= ':' . $params['db']['port'];
        }

        $dsn = 'mysql:dbname=' . $params['db']['name'] . ';host=' . $address;
        $user = $params['db']['user'];
        $password = $params['db']['password'];
        $pdo = new \PDO($dsn, $user, $password);
        $this->setPdo($pdo);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    private function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
}