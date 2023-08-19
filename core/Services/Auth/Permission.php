<?php

namespace Core\Services\Auth;

class Permission extends Auth
{

    private static $instance = null;

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
     * @param string $section розділ сайту чи меню
     * @param int $uid users id
     * @return bool
     */
    public static function access(string $section, int $uid=0): bool
    {
        return true;
    }


}