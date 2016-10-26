<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Phone;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;

class UserRepository
{
    const SAVE_NEW_USER_QUERY = 'INSERT INTO users(user, pass, first_name, last_name, phone, company, isadmin, login_atempts, time_out) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
    const SAVE_EXISTING_USER_QUERY = 'UPDATE users SET email=?, first_name=?, last_name=?, isadmin=?, phone=?, company=?, login_atempts=?, time_out=? WHERE id=?';
    const FIND_USER_QUERY = 'SELECT * FROM users WHERE user=?';
    const DELETE_USER_QUERY = 'DELETE FROM users WHERE user=?';
    const FIND_ALL_USERS_QUERY = 'SELECT * FROM users';
    const UPDATE_LOGIN_ATEMPTS = 'UPDATE users SET login_atempts=? WHERE id=?';
    const FIND_LOGIN_ATEMPTS = 'SELECT login_atempts FROM users WHERE user=?';
    const UPDATE_TIME_OUT = 'UPDATE users SET time_out=? WHERE id=?';
    const FIND_TIME_OUT = 'SELECT time_out FROM users WHERE user=?';

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function makeUserFromRow(array $row)
    {
        $user = new User($row['user'], $row['pass'], $row['first_name'], $row['last_name'], $row['phone'], $row['company']);
        $user->setUserId($row['id']);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setPhone($row['phone']);
        $user->setCompany($row['company']);
        $user->setIsAdmin($row['isadmin']);

        if (!empty($row['email'])) {
            $user->setEmail(new Email($row['email']));
        }

        if (!empty($row['phone'])) {
            $user->setPhone(new Phone($row['phone']));
        }

        return $user;
    }

    //Modified with PDO prepared statement
    public function getNameByUsername($username)
    {
        $query = $this->pdo->prepare(self::FIND_USER_QUERY);
        $query->execute(array($username));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $name = $row['first_name'] + " " + $row['last_name'];
        return $name;
    }

    //Modified with PDO prepared statement
    public function findByUser($username)
    {
        $query = $this->pdo->prepare(self::FIND_USER_QUERY);
        $query->execute(array($username));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($row === false) {
            return false;
        }

        return $this->makeUserFromRow($row);
    }

    //Modified with PDO prepared statement
    public function deleteByUsername($username)
    {
        $query = $this->pdo->prepare(self::DELETE_USER_QUERY);
        $query->execute(array($username));
        return $query->rowCount();
    }

    //Modified with PDO prepared statement
    public function all()
    {
        $query = $this->pdo->prepare(self::FIND_ALL_USERS_QUERY);
        $query->execute();
        $rows = $query->fetchAll();
        
        if ($rows === false) {
            return [];
        }

        return array_map([$this, 'makeUserFromRow'], $rows);
    }

    public function save(User $user)
    {
        if ($user->getUserId() === null) {
            return $this->saveNewUser($user);
        }

        $this->saveExistingUser($user);
    }

    //Modified with PDO prepared statement
    public function saveNewUser(User $user)
    {
        $query = $this->pdo->prepare(self::SAVE_NEW_USER_QUERY);
        return $query->execute(array($user->getUsername(), $user->getHash(), $user->getFirstName(), $user->getLastName(), $user->getPhone(), $user->getCompany(), $user->isAdmin(), $user->getLogin_atempts(), $user->getTime_out()));
    }

    //Modified with PDO prepared statement
    public function saveExistingUser(User $user)
    {
        $query = $this->pdo->prepare(self::SAVE_EXISTING_USER_QUERY);
        return $query->execute(array($user->getEmail(), $user->getFirstName(), $user->getLastName(), $user->isAdmin(), $user->getPhone(), $user->getCompany(), $user->getLogin_atempts(), $user->getTime_out(), $user->getUserId()));
    }

    //Modified with PDO prepared statement
    public function getLoginAtempts($username)
    {
        $query = $this->pdo->prepare(self::FIND_LOGIN_ATEMPTS);
        $query->execute(array($username));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if($row == false){
            return false;
        }

        return $row['login_atempts'];

    }

    //Modified with PDO prepared statement
    public function updateLoginAtempts($username)
    {
        $user = $this->findByUser($username);
        $login_atempts = $this->getLoginAtempts($username);

        if($login_atempts == 2){
            $this->updateTimeOut($user);
        }

        if($user != false){
            $query = $this->pdo->prepare(self::UPDATE_LOGIN_ATEMPTS);
            return $query->execute(array($login_atempts+1,$user->getUserId()));
        }

        return;
    }

    public function updateTimeOut(User $user)
    {
        $query = $this->pdo->prepare(self::UPDATE_TIME_OUT);
        return $query->execute(array(date("Y-m-d H:i:s", strtotime("+1 minutes")), $user->getUserId()));
    }

    public function getTimeOut($username)
    {
        $query = $this->pdo->prepare(self::FIND_TIME_OUT);
        $query->execute(array($username));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if($row == false){
            return false;
        }

        return $row['time_out'];
    }

    public function clearTimeOut($username)
    {
        $user = $this->findByUser($username);

        if($user != false){
            $query = $this->pdo->prepare(self::UPDATE_TIME_OUT);
            return $query->execute(array(null, $user->getUserId()));
        }

        return;
    }

    public function clearLoginAtempts($username)
    {
        $user = $this->findByUser($username);

        if($user != false){
            $query = $this->pdo->prepare(self::UPDATE_LOGIN_ATEMPTS);
            return $query->execute(array(0, $user->getUserId()));
        }

        return;
    }

}
