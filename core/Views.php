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
        "header" => ["googleFont" => [], "fontAwesomeIcons" => [], "adminLTE" => [], "Ionicons" => [], "toastr" => []],
        "footer" => ["jQuery" => [], "bootstrap" => [], "jqueryValidation" => [], "adminLTE" => [], "sweetalert2" => [], "toastr" => [], "app" => []
        ]
    ];

    /**
     * Обробка вхідних даних
     * @param string $template сторінка запиту
     * @param array $data вхідні дані
     */
    protected function render(string $template, array $data = []): void
    {

        // self::$User = new User;

        $cookie = Cookie::getInstance();
        // отримати список помилок
        $errors = $cookie::getCookie("errors");

        // Підключені плагіни на сторінці
        if (isset($data['plugins']) && is_array($data['plugins']))
            self::pluginsSets($data['plugins']);

        // Імпортувати змінні
        self::$pageOptions = array_merge(self::$pageOptions, $data);
        extract(self::$pageOptions);

        // Cписок плагінів
        include(APP_PATH . "/config/plugins.php");

        // очищення помилок
        $cookie::deleteCookie("errors");
        // Перевірка прав доступу і включення сторінки

        include_once($this->includePath($template));

    }

    private static function pluginsSets(array $customPlugins): void
    {
        if (isset($customPlugins["header"]) && is_array($customPlugins["header"]))
            self::$plugins["header"] = array_merge(self::$plugins["header"], $customPlugins["header"]);

        if (isset($customPlugins["footer"]) && is_array($customPlugins["footer"]))
            self::$plugins["footer"] = array_merge(self::$plugins["footer"], $customPlugins["footer"]);
    }

    /**
     * виводить обрані теги
     * @param string $layout розташування header/footer
     * @return void
     */
    private function getPlugins(string $layout): void
    {
        foreach (self::$plugins[$layout] as $plugin => $params) {
            if (function_exists($plugin)) {
                if (is_array($params) && count($params) > 0) {
                    foreach ($params as $key => $val)
                        echo call_user_func_array($plugin, [$layout, [$key => $val]]) . "\n";
                } else {
                    echo call_user_func_array($plugin, [$layout, $params]) . "\n";
                }
            }
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
        return implode(" | ", [self::$pageOptions['title'], self::$pageOptions['AppName']]);
    }

    /**
     * Підключення сторінки
     * @param string $template сторінка
     */
    private function includePath(string $template): string
    {
        return APP_PATH . "/views/" . $template . ".php";
    }

    protected static function redirect(string $url = APP_URL_F): void
    {
        header('Location: ' . $url);
    }

    /**
     * Виводить бічне меню;
     */
    protected function sideBarMenu(): void
    {
        /**
         * href - # or link,
         * icon - Add icons to the links using the .nav-icon class with font-awesome or any other icon font library
         * title - текст меню
         * badge - масив (текст, колір)
         * permission
         */
        $menu[0] = ["href" => APP_URL_F, "icon" => "fas fa-tachometer-alt", "title" => _("Dashboard")];
        $menu[] = ["href" => [
            ["href" => APP_URL_F . "/workflow?p=students",
                "icon" => "fas fa-graduation-cap",
                "title" => _("Students")],
            ["href" => APP_URL_F . "/workflow?p=users",
                "icon" => "fas fa-user",
                "title" => _("Workflow")],
            ["href" => APP_URL_F . "/workflow?p=groups",
                "icon" => "fas fa-users",
                "title" => _("Groups")],
            ["href" => APP_URL_F . "/workflow?p=subjects",
                "icon" => "fas fa-bookmark",
                "title" => _("Subjects")],
        ], "icon" => "fas fa-university", "title" => _("Workflow")];
        $menu[] = ["href" => APP_URL_F . "/synchronizer", "permission" => ["moder" => true], "icon" => "fas fa-download", "title" => _("Synchronizer")];

        self::mParse($menu);

    }

    /**
     * @param array $menu виводить елементи бічного меню
     * @return void
     */
    private static function mParse(array $menu): void
    {
        foreach ($menu as $item) {

            extract(array_merge(["href" => "#", "icon" => null, "title" => null, "badge" => [], "permission" => []], $item));
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
     * @param array $permission доcтуп до пукту менб для давного користувача
     * @param int|null $tree визначення для підпеню
     *
     * @return void
     *
     */
    private static function sideBarMenuItemConstruct(string $title, string $href, string $icon, array $badge, array $permission, int $tree = null): void
    {
        if (!self::Permissions()::roleAccess($permission))
            return;

        if (!is_null($icon))
            $icon = "<i class='nav-icon $icon'></i> ";

        if (!is_null($tree))
            $tree = "<i class=\"fas fa-angle-left right\"></i>";

        // $badge = !is_null($badge) ?: "<i class=\"fas fa-angle-left right\"></i>";

        echo "<a href='{$href}' class='nav-link'>{$icon}<p>";
        echo $title;
        echo $tree;
//        echo $badge;
        echo "</p></a>";
    }

}
