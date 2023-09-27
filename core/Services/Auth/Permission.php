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
    public static function access(string $section, string $el = null): bool
    {
        if (parent::isAdmin())
            return true;

        return true;

    }

    public static function roleAccess($list = null): bool
    {

        if (parent::isAdmin())
            return true;

        return isset($list[parent::userRole()]);
    }

    public static function pageAccess($page)
    {
        if (parent::isAdmin())
            return true;

        switch ($page) {
            case("hoursplan"):
                return parent::userRole() == "moder";
                break;
            default:
                return true;
        }
}

    public static function actionAcess($action)
    {
        if (parent::isAdmin())
            return true;

        switch ("") {
            case("subjectEdit"):
                return parent::userRole() == "moder";
                break;
            default:
                return true;
        }
    }

}