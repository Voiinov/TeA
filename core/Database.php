<?php

namespace Core;

class Database{
    private static $instance = null;
    private $connection;

    private $host = '127.0.0.1';
    private $dbname = 'tea_db';
    private $username = 'root';
    private $password = '';

    private function __construct(){
        try {
            $this->connection = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(_("Database connection error: ") . $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->connection;
    }

    /**
     * Повертає єдиний екземпляр класу Database. При створенні об'єкту класу, відбувається з'єднання з базою даних через розширення PDO. 
     * Якщо з'єднання неможливо встановити, виводиться повідомлення про помилку.
     */
    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * SQL-запит до бази даних. Параметри запиту можна передати у вигляді масиву $params.
     * @param string $sql
     * @param array $params параметри запиту
    */
    public function query($sql, $params = []){
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (\PDOException $e) {
            die("E0001 - " . _("Database query error: ") . $e->getMessage());
        }
    }

    public function queryArray($data){
        try {
            $statement = $this->connection->prepare($data['sql']);
            $statement->execute($data['params']);
            return $statement;
        } catch (\PDOException $e) {
            die("E0002 -" . _("Database query error: ") . $e->getMessage());
        }
    }

    public function getCount($table,$field,$value){
        $this->query("SELECT COUNT(email) AS UniqueEmail FROM");
    }

    /**
     * Повертає ідентифікатор останнього вставленого рядка у таблицю.
     */
    public function lastInsertId(){
        return $this->connection->lastInsertId();
    }

    public function getAppSets($g=1){

    }

}

?>