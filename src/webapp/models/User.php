<?php

namespace tdt4237\webapp\models;

class User
{

    protected $userId  = null;
    protected $username;
    protected $hash;
    protected $salt;
    protected $firstName;
    protected $lastName;
    protected $phone;
    protected $company = null;
    protected $email   = null;
    protected $isAdmin = 0;
    protected $login_atempts;
    protected $time_out;

    function __construct($username, $hash, $salt, $firstName, $lastName, $phone, $company)
    {
        $this->username = $username;
        $this->hash = $hash;
        $this->salt = $salt;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->company = $company;
        $this->login_atempts = 0;
        $this->time_out = null;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

     public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

     public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company){
        $this->company = $company;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

     public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;

    }

    public function getLogin_atempts() {
        return $this->login_atempts;
    }

    public function setLogin_atempts($login_atempts) {
        $this->login_atempts = $login_atempts;
    }

    public function getTime_out() {
        return $this->time_out;
    }

    public function setTime_out($time_out) {
        $this->time_out = $time_out;
    }

    public function isAdmin()
    {
        return $this->isAdmin === '1';
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

}
