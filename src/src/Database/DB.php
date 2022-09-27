<?php

namespace AkDevTodo\Backend\Database;

use AkDevTodo\Backend\Tools\Env;

class DB
{

    private string $host;
    private string $port;
    private string $dbName;
    private string $userName;
    private string $password;

    public function __construct()
    {
        $this->host = Env::get('DB_HOST');
        $this->port = Env::get('DB_PORT');
        $this->dbName = Env::get('DB_DATABASE');
        $this->userName = Env::get('DB_USERNAME');
        $this->password = Env::get('DB_PASSWORD');
    }

    /**
     * @return \PDO
     */
    private function connect(): \PDO
    {
        static $connection = null;

        if ($connection === null) {
            try {
                $connection = new \PDO(
                    "mysql:host=$this->host:$this->port; dbname=$this->dbName",
                    $this->userName,
                    $this->password
                );
            } catch (\PDOException $e) {
            }
        }

        return $connection;
    }


    /**
     * @param string $query
     * @param array $params
     * @param string|null $wrapper
     * @return array|void
     */
    public function execute(string $query, array $params = [], string $wrapper = null)
    {
        try {
            $stmt = $this->connect()->prepare($query);

            if ($wrapper === null) {
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            } else {
                $stmt->setFetchMode(\PDO::FETCH_CLASS, $wrapper);
            }

            if (count($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }

            $result = [];
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }

            return $result;

        } catch (\PDOException $e) {
            var_dump($e->getMessage());
        }
    }


    /**
     * @param string $table
     * @return array
     */
    public function columns(string $table): array
    {
        $query = "SELECT DISTINCT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = '$table'
    AND TABLE_SCHEMA = '$this->dbName';";

        $columns = [];
        foreach ($this->execute($query) as $items) {
            if ($items['COLUMN_NAME']) {
                $columns[] = $items['COLUMN_NAME'];
            };
        }

        return $columns;
    }
}