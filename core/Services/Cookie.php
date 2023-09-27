<?php 
namespace Core\Services;

class Cookie {

    private static $instance = null;

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

    // Метод для встановлення cookie
    public static function setCookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = true) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    // Повертає помилки і видаляє cookie з помилками
    public static function getErrors($assoc=true){
        return self::getCookie("errors");
    }

    public static function isCookie($name){
        return $_COOKIE[$name] ?? false;
    }
    // Метод для отримання значення cookie
    public static function getCookie($name) {
        return $_COOKIE[$name] ?? null;
    }

    // Метод для видалення cookie
    public static function deleteCookie($name, $path = '/', $domain = '') {
        if (isset($_COOKIE[$name])) {
            setcookie($name, '', time() - 3600, $path, $domain);
            unset($_COOKIE[$name]);
        }
    }
}

?>