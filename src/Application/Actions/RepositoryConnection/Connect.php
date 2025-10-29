<?php

namespace App\Application\Actions\RepositoryConnection;

use PDO;

class Connect
{
    private $db;

    public function __construct()
    {
        $dbHost = 'localhost';
        $dbUser = 'postgres';
        $dbPass = 'admin';
        $dbName = 'test';
        $dbtype = 'pgsql';
        $dbport = '5432';
        $connect = $dbtype . ":host=" . $dbHost . ";dbname=" . $dbName . ";port=" . $dbport;
        $dbConnecion = new PDO($connect, $dbUser, $dbPass);
        $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $dbConnecion;
    }

    public function getConnection(): PDO
    {
        return $this->db;
    }
}