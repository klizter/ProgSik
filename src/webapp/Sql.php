<?php

namespace tdt4237\webapp;

use tdt4237\webapp\models\User;

class Sql
{
    static $pdo;

    function __construct()
    {
    }

    /**
     * Create tables.
     */
    static function up()
    {
        $q1 = "CREATE TABLE users (id INTEGER PRIMARY KEY, user VARCHAR(50), pass VARCHAR(50), salt VARCHAR(50), email varchar(50) default null, first_name varchar(50), last_name varchar(50), phone varchar (8), company varchar(50), isadmin INTEGER, login_atempts INTEGER, time_out VARCHAR(50));";
        $q6 = "CREATE TABLE patent (patentId INTEGER PRIMARY KEY AUTOINCREMENT, company TEXT NOT NULL, title TEXT NOT NULL, file TEXT NOT NULL, description TEXT NOT NULL, date TEXT NOT NULL, FOREIGN KEY(patentId) REFERENCES users(company));";

        self::$pdo->exec($q1);
        self::$pdo->exec($q6);

        print "[tdt4237] Done creating all SQL tables.".PHP_EOL;

        self::insertDummyUsers();
        self::insertPatents();
    }

    static function insertDummyUsers(){

        $q1 = "INSERT INTO users(user, pass, salt, isadmin, first_name, last_name, phone, company, email, login_atempts, time_out) VALUES ('Thecarbonbreezes', 'dded1fe57b83acb92adf139bcc8514e3c25f27ff9906257e79840f330a2023f1', '0Bhs1s1Mly0=', 1, 'Bjarni', 'Torgmund', '32187625', 'Patentsy AS', 'ceobjarnitorgmund@patentsy.com',0, null)";

        self::$pdo->exec($q1);

        print "[tdt4237] Done inserting admin.".PHP_EOL;
    }

    static function insertPatents() {
        $q4 = "INSERT INTO patent(company, title, file, description, date) VALUES ('Patentsy AS', 'Search System', 'web/uploads/test.txt', 'New algorithm making search as fast as speed of light.', '20062016')";
        $q5 = "INSERT INTO patent(company, title, file, description, date) VALUES ('Patentsy AS', 'New litteum battery technology', 'web/uploads/test.txt', 'A new technology that will take batteries through a new revolution.', '26072016')";

        self::$pdo->exec($q4);
        self::$pdo->exec($q5);
        print "[tdt4237] Done inserting patents.".PHP_EOL;

    }

    static function down()
    {
        $q1 = "DROP TABLE users";
        $q4 = "DROP TABLE patent";

        self::$pdo->exec($q1);
        self::$pdo->exec($q4);

        print "[tdt4237] Done deleting all SQL tables.".PHP_EOL;
    }
}
try {
    // Create (connect to) SQLite database in file
    Sql::$pdo = new \PDO('sqlite:app.db');
    // Set errormode to exceptions
    Sql::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit();
}
