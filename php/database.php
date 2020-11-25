<?php


class Database {

    /**
     * @var PDO $connection
     */
    private $connection;

    /**
     * @var PDOStatement $stmt
     */
    private $stmt;

    /**
     * Database constructor. Starts database connection
     */
    public function __construct() {

        $config = require "../config/database.php";


        try {
            $this->connection = new PDO("mysql:dbname={$config["DATABASE"]};charset={$config["CHARSET"]};host={$config["HOSTNAME"]}", $config["USERNAME"], $config["PASSWORD"]);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch(PDOException $e) {

            exit($e);
        }
    }

    /**
     * @param string $query
     * @param array $bindable
     * @return Database $this
     */
    public function query($query, $bindable = []) {

        try {
            $this->stmt = $this->connection->prepare($query);

            if(isset($bindable) && !empty($bindable)){
                $this->stmt->execute($bindable);
            }
            else {
                $this->stmt->execute();
            }

        }
        catch(PDOException $e) {

            exit($e);
        }

        return $this;
    }

    public function fetchAssoc() {

        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->stmt = NULL;

        return $data;
    }

    public function fetchArray() {

        $data = $this->stmt->fetchAll(PDO::FETCH_NUM);

        $this->stmt = NULL;

        return $data;
    }

    public function affectedRows() {

        return $this->stmt->rowCount();
    }

    public function __destruct() {

        $this->connection = NULL;
    }

}
