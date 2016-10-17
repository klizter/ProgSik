<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Patent;
use tdt4237\webapp\models\PatentCollection;

/*

OWASP - Mitigating SQL injection attacks
https://www.owasp.org/index.php/SQL_Injection_Prevention_Cheat_Sheet

*/


class PatentRepository
{
    //Prepared queries used by PDO
    //http://php.net/manual/en/pdo.prepare.php
    const FIND_PATENT_QUERY = 'SELECT * FROM patent WHERE patentId=?';
    const FIND_ALL_PATENTS_QUERY = 'SELECT * FROM patent';
    const DELETE_PATENT_QUERY = 'DELETE FROM patent WHERE patentid=?';
    const SAVE_PATENT_QUERY = 'INSERT INTO patent (company, date, title, description, file) VALUES (?, ?, ?, ?, ?)';

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function makePatentFromRow(array $row)
    {
        $patent = new Patent($row['patentId'], $row['company'], $row['title'], $row['description'], $row['date'], $row['file']);
        $patent->setPatentId($row['patentId']);
        $patent->setCompany($row['company']);
        $patent->setTitle($row['title']);
        $patent->setDescription($row['description']);
        $patent->setDate($row['date']);
        $patent->setFile($row['file']);

        return $patent;
    }

    //Modified with PDP prepare and execute statements
    public function find($patentId)
    {

        $query = $this->pdo->prepare(self::FIND_PATENT_QUERY);
        $query->execute(array($patentId));
        $row = $query->fetch();

        if($row === false) {
            return false;
        }

        return $this->makePatentFromRow($row);
    }

    //Modified with PDO prepare and execute statements
    public function all()
    {
        $query = $this->pdo->prepare(self::FIND_ALL_PATENTS_QUERY);
        $query->execute();

        $fetch = $query->fetchAll();
        if(count($fetch) == 0) {
            return [];
        }

        return new PatentCollection(
            array_map([$this, 'makePatentFromRow'], $fetch)
        );
    }

    //Modified with PDO prepare and execute statements
    public function deleteByPatentid($patentId)
    {
        $query = $this->pdo->prepare(self::DELETE_PATENT_QUERY);
        $query->execute(array($patentId));
        return $query->rowCount();
    }

    //Modified with PDO prepare and execute statements
    public function save(Patent $patent)
    {
        $title          = $patent->getTitle();
        $company        = $patent->getCompany();
        $description    = $patent->getDescription();
        $date           = $patent->getDate();
        $file           = $patent->getFile();

        if ($patent->getPatentId() === null) {
            $query = $this->pdo->prepare(self::SAVE_PATENT_QUERY);
        }

        $query->execute(array($company, $date, $title, $description, $file));
        return $this->pdo->lastInsertId();
    }
}
