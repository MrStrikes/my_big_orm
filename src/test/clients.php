<?php

namespace test;

use MBO\MBOEntity;

class clients extends MBOEntity
{

    private $tableName = "clients";

    private $id;

    private $lastname;

    private $firstname;

    private $address;

    private $city;

    private $country_id;

    private $phone;

    private $email;

    private $col = ['id', 'lastname', 'firstname', 'address', 'city', 'country_id', 'phone', 'email'];

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return clients
     */
    public function setId($id): clients
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return clients
     */
    public function setLastname($lastname): clients
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return clients
     */
    public function setFirstname($firstname): clients
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return clients
     */
    public function setAddress($address): clients
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return clients
     */
    public function setCity($city): clients
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param mixed $country_id
     * @return clients
     */
    public function setCountryId($country_id): clients
    {
        $this->country_id = $country_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return clients
     */
    public function setPhone($phone): clients
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return clients
     */
    public function setEmail($email): clients
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getCol(): array
    {
        return $this->col;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName): clients
    {
        $this->tableName = $tableName;
        return $this;
    }

}