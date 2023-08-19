<?php

namespace Core;

use Core\Services\Auth\Permission;
use Core\Services\Cookie;

class Views
{
    // Параметри сторінки
    protected static array $pageOptions = [
        "AppName" => "Teacher Assistant",
        "AppNameShort" => "TeA",
        "AppNameLogo" => "<strong>Teacher</strong>Assistant",
        "AppLogo" => APP_URL_F . "/storage/img/TeacherAssistantLogo.png",
        "title" => "TeA"
    ];

    // підключені плагіни
    protected static array $plugins = [
            "header" => ["googleFont" => [], "fontAwesomeIcons" => [], "adminLTE" => [], "Ionicons" => []],
            "footer" => ["jQuery" => [], "bootstrap" => [], "jqueryValidation" => [], "adminLTE" => [], "app" => []
            ]
        ];
    protected $cookie;
    protected static $User;
    protected static $DB;
    protected static $permission;


    /**
     * Обробка вхідних даних
     * @param $template сторінка запиту
     * @param array $data вхідні дані
     */
    protected function render(string $template, $data = [])
    {

        // self::$User = new User;

        $cookie = Cookie::getInstance();
        // отримати список помилок
        $errors = $cookie::getCookie("errors");

        // Підключені плагіни на сторінці
        if(isset($data['plugins']) && is_array($data['plugins']))
            self::pluginsSets($data['plugins']);

        // Імпортувати змінні
        self::$pageOptions = array_merge(self::$pageOptions, $data);
        extract(self::$pageOptions);

        // Cписок плагінів
        include(APP_PATH . "/config/plugins.php");

        // очищення помилок
        $cookie::deleteCookie("errors");
        // Перевірка прав доступу і включення сторінки

        include_once($this->includePath($template, $module));

    }

    private static function pluginsSets(array $customPlugins): void
    {
        if(is_array($customPlugins["header"]))
            self::$plugins["header"] = array_merge(self::$plugins["header"],$customPlugins["header"]);

        if(is_array($customPlugins["footer"]))
            self::$plugins["footer"] = array_merge(self::$plugins["footer"],$customPlugins["footer"]);
    }

    /**
     * виводить обрані теги
     * @param string $layout розташування header/footer
     * @return void
     */
    private function getPlugins(string $layout): void
    {
        foreach (self::$plugins[$layout] as $plugin => $params) {
            if (function_exists($plugin))
                echo call_user_func_array($plugin, [$layout, $params]) . "\n";
        }

    }

    public static function Permissions(): Permission
    {
        return Permission::getInstance();
    }

    public static function DataBase(): Database
    {
        return Database::getInstance();
    }

    public function pageTitle(): string
    {
        return self::$pageOptions['title'] . " | " . "TeA";
    }

    /**
     * повертає html тег
     * @param string $tag вид тегу (meta/script/link)
     * @param $href посилання
     * @param array $attr атрибути і/або js код
     * @return string
     */
//    private function tagConstract($tag, $href, $attr): string
//    {
//        if ($tag == "link") {
//            foreach ($href as $link)
//                return "<link href=\"{$link}\""
//                    . (empty($attr) ? "" : array_map(function ($key, $val) {
//                        return "{$key}=\"{$val}\"\n";
//                    }, array_keys($attr), $attr))
//                    . ">";
//        }
//
//        if ($tag == "jscode") {
//            foreach ($href as $link)
//                return "<script src=\"{$link}\""
//                    . (empty($attr) ? "" : array_map(function ($key, $val) {
//                        return "{$key}=\"{$val}\"\n";
//                    }, array_keys($attr), $attr))
//                    . ">";
//        }
//
//        if ($tag == "jscode") {
//            return "<script"
//                . (empty($attr) ? "" : array_map(function ($key, $val) {
//                    return "{$key}=\"{$val}\"\n";
//                }, array_keys($attr), $attr))
//                . "{$href}</script>";
//        }
//
//        if ($tag == "meta") {
//
//        }
//
//    }

    /**
     * Підключення сторінки
     * @param string $templapte сторінка
     */
    private function includePath(string $template): string
    {
        $path = APP_PATH . "/views/" . $template . ".php";
        return $path;
    }

    protected static function redirect(string $url = APP_URL_F)
    {
        header('Location: ' . $url);
    }

    /**
     * Виводить бічне меню;
     */
    protected function sideBarMenu(): void
    {
        // href - # or link,
        // icon - Add icons to the links using the .nav-icon class with font-awesome or any other icon font library 
        // title - текст меню
        // badge - масив (текст, колір)
        // permission
        $menu[0] = ["id" => "dshboard", "href" => APP_URL_F, "icon" => "fas fa-tachometer-alt", "title" => "Dashboard"];
        $menu[] = ["id" => "journal", "href" => APP_URL_F . "/journal", "icon" => "fas fa-tachometer-alt", "title" => "Journal"];
        $menu[] = ["id" => "groups", "href" => APP_URL_F . "/groups", "icon" => "fas fa-tachometer-alt", "title" => "Groups"];
        $menu[] = ["href" => [
            ["href" => APP_URL_F,
                "icon" => "fas fa-tachometer-alt",
                "title" => "Учні"],
            ["href" => APP_URL_F,
                "icon" => "fas fa-tachometer-alt",
                "title" => "Викладачі"],
        ], "icon" => "fas fa-users", "title" => "Users"];

        self::mParse($menu);

    }

    /**
     * @param array $menu виводить елементи бічного меню
     * @return void
     */
    private static function mParse($menu): void
    {
        foreach ($menu as $item) {

            extract(array_merge(["href" => "#", "icon" => null, "title" => null, "badge" => null, "permission" => null], $item));

            echo "<li class='nav-item'>";
            if (is_array($item["href"])) {
                self::sideBarMenuItemConstruct($title, "#", $icon, $badge, $permission, 1);
                echo "<ul class='nav nav-treeview'>";
                self::mParse($item["href"]);
                echo "</ul>";
            } else {
                self::sideBarMenuItemConstruct($title, $href, $icon, $badge, $permission);
            }
            echo "</li>";
        }
    }

    /**
     * @param string $title текст меню
     * @param string $href посилання
     * @param string $icon awesome icon
     * @param array $badge badges array('text'=>'Some text','class'=>'info/danger/success etv.')
     * @param bool $permission доcтуп до пукту менб для давного користувача
     * @param int $tree визначення для підпеню
     *
     * @return void
     *
     */
    private static function sideBarMenuItemConstruct($title, $href, $icon, $badge, $permission, $tree = null)
    {
        if (self::Permissions()::access($href)) {

            if (!is_null($icon))
                $icon = "<i class='nav-icon $icon'></i> ";

            if (!is_null($tree))
                $tree = "<i class=\"fas fa-angle-left right\"></i>";

            // $badge = !is_null($badge) ?: "<i class=\"fas fa-angle-left right\"></i>";

            echo "<a href='{$href}' class='nav-link'>{$icon}<p>";
            echo $title;
            echo $tree;
            echo $badge;
            echo "</p></a>";
        }

    }
}


?>