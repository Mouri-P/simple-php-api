<?php

class DatabaseConfig
{
    private $host = "us-cdbr-east-04.cleardb.com";
    private $username = "bbb4b18e76ed30";
    private $password = "7539e682";
    private $db_name = "heroku_77f406429dc5686";
    private $connection;

    public function connect()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            $this->connection = null;
            echo 'Connection Error: ' . $error->getMessage();
        }
        return $this->connection;
    }
}
