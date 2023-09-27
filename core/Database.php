<?php

namespace Core;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $param = $this->getSettings();
            $this->connection = new \PDO(
                "mysql:host={$param['host']};dbname={$param['dbname']}",
                $param['username'],
                $param['password']
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(_("Database connection error: ") . $e->getMessage());
        }
    }

    private function getSettings(): array
    {
        return require(APP_PATH . "/config/database.php");
    }

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Повертає єдиний екземпляр класу Database. При створенні об'єкту класу, відбувається з'єднання з базою даних через розширення PDO.
     * Якщо з'єднання неможливо встановити, виводиться повідомлення про помилку.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * SQL-запит до бази даних. Параметри запиту можна передати у вигляді масиву $params.
     * @param string $sql
     * @param array $params параметри запиту
     * @param bool $fetch
     */
    public function query(string $sql, array $params = [], bool $fetch = false)
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $fetch ? $statement->fetchAll(\PDO::FETCH_ASSOC) : $statement;
        } catch (\PDOException $e) {
            die("E0001 - " . _("Database query error: ") . $e->getMessage());
        }
    }

    public function insert(string $table, array $fields, array $data, $multipledata = false)
    {

        $sql = "INSERT INTO {$table} ( " . implode(",", $fields) . ") VALUES (:" . implode(",:", $fields) . ")";
        try {
            $statement = $this->connection->prepare($sql);
            if ($multipledata) {
                foreach ($data as $row)
                    $statement->execute($row);
            } else {
                $statement->execute($data);
            }

            return $this->lastInsertId();

        } catch (\PDOException $e) {
            die("E0001 - " . _("Database query error: ") . $e->getMessage());
        }
    }

    public function queryArray($data)
    {
        try {
            $statement = $this->connection->prepare($data['sql']);
            $statement->execute($data['params']);
            return $statement;
        } catch (\PDOException $e) {
            die("E0002 -" . _("Database query error: ") . $e->getMessage());
        }
    }

    public function getCount($table, $field, $value)
    {
        $this->query("SELECT COUNT(email) AS UniqueEmail FROM");
    }

    public function getOptionsValue(string $option, string $value = null)
    {
        $options = [];
        $sql = "SELECT * FROM options WHERE option = :option";
        foreach ($this->query($sql, ["option" => $option], true) as $data) {
            $options[$data['value']] = ["description"=>$data['description'],'level'=>$data['level']];
        }

        return $options;

    }

    /**
     * Повертає ідентифікатор останнього вставленого рядка у таблицю.
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function getAppSets($g = 1)
    {

    }

}

?>