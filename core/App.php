<?php

namespace Core;

use Core\Services\Auth\Auth;
use Core\Services\Cookie;
use Core\Services\Mods;

class App
{

    static Router $router;

//    protected Auth $Auth;

    static Cookie $cookie;

    public function __construct()
    {
        // Ініціалізація роутера
        self::$router = new Router();
        // Ініціалізація UserModel
//        $this->Auth = new Auth();
        // Очищення списку помилок
        self::$cookie = new Cookie();
    }

    public function run()
    {

        Auth::init();

//        self::$router->addRoute("about", 'DashboardController', 'about');

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            if ($_POST['submit'] == "register" || $_POST['submit'] == "signin") {
                $action = $_POST['submit'];
                if (Auth::$action($_POST)) {
                    header("Location: " . APP_URL_F);
                }
            }
        }
        // Прибрати GET-параметри із URL (якщо є)
        //        $url = strtok(str_replace(APP_URL_B, "", $url), '?');
        $url = strtok(mb_substr(str_replace(APP_URL_B, "", $_SERVER['REQUEST_URI']), 1), '?');

        if (Auth::isLoggedIn()) {
            self::$router->addRoute("profile", 'DashboardController', 'profile');

            Mods::init();
        } else {
            $this::$router->addRoute('auth', 'AuthController', 'login');
            $this::$router->addRoute('register', 'AuthController', 'register');
            $this::$router->addRoute('reset', 'AuthController', 'reset');
            $this::$router->addRoute('new_password', 'AuthController', 'newPassword');
            $this::$router->addRoute('new_password_sent', 'AuthController', 'newPasswordSent');

            if ($url == "register")
                $url = "auth/register";
            elseif ($url == "reset")
                $url = "reset";
            elseif ($url == "new_password")
                $url = "new_password";
            elseif ($url == "new_password_sent")
                $url = "new_password_sent";
            else
                $url = "auth/login";

        }

        self::$router->route($url);

    }

}

?>