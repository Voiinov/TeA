<?php

namespace Core\Services;

use App\Models\Auth;
use App\Models\data;
use App\Models\id;
use Core\Database;

abstract class User{
    private static $instance = null;
    protected $db;
    public $Auth;

    abstract protected function isLoggedIn();

    public function __construct(){
        
        $this->db = Database::getInstance();

        $this->Auth = new Auth($this->db->getConnection());
    }

    /**
     * Отримати всіх користувачів з бази даних
     */
    public function getAllUsers(){
        $sql = 'SELECT * FROM users';
        $result = $this->db->query($sql);

        // Повернути результат запиту в масиві
        return $result->fetchAll();
    }

    /**
     * Отримати користувача за ідентифікатором з бази даних
     * @param id користувача
     */
    public function getUserById($id){
        $sql = 'SELECT * FROM users WHERE id = :id';
        $params = array(':id' => $id);
        $result = $this->db->query($sql, $params);
        // Повернути результат запиту як об'єкт або null, якщо користувач не знайдений
        return $result->fetch();
    }

    /**
     * Створити нового користувача в базі даних
     * @param data
     */
    public function createUser($data){
        $sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $result = $this->db->query($sql, $data);

        // Повернути ідентифікатор нового користувача
        return $this->db->lastInsertId();
    }

    /**
     * Оновити існуючого користувача в базі даних за ідентифікатором
     * 
     * @param id - ідектифікатор
     * @param data
     */
    public function updateUser($id, $data){
        $sql = 'UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id';
        $data['id'] = $id;
        $result = $this->db->query($sql, $data);

        // Повернути кількість змінених рядків або false, якщо оновлення не вдалося
        return $result->rowCount();
    }

    /**
     * Видалити користувача з бази даних за ідентифікатором
     * 
     * @param id ідентифікатор
     */
    public function deleteUser($id){
        $sql = 'DELETE FROM users WHERE id = :id';
        $params = array(':id' => $id);
        $result = $this->db->query($sql, $params);

        // Повернути кількість видалених рядків або false, якщо видалення не вдалося
        return $result->rowCount();
    }

    /**
     * Повертає посилання на логотип користувача
     */
    public static function avatar(){
        return APP_URL_F . "/storage/img/avatarDefault-5.png";
    }


    public static function name(){
        return $_COOKIE["username"] ?? "User";
    }



}

?>