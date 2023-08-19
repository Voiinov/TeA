<?php

namespace Core;

use Core\Services\Auth\Auth;
use Core\Services\Cookie;
use Core\Services\Mods;

class App{
    
    static $router;

    protected $Auth;

    static $cookie;

    public function __construct(){
        // Ініціалізація роутера
        self::$router = new Router();
        // Ініціалізація UserModel
        $this->Auth = new Auth();
        // Очищення списку помилок
        self::$cookie = new Cookie();
    }

    public function run(){
        
        self::$router->addRoute("about", 'DashboardController', 'about');
        
        // Отримання поточного URL
        $url = $_SERVER['REQUEST_URI'];

        if($this->Auth::isLoggedIn()){
            Mods::init();
        }else{

            $this::$router->addRoute('/auth', 'AuthController', 'login');
            $this::$router->addRoute('/register', 'AuthController', 'register');

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

                if($_POST['submit']=="register" || $_POST['submit']=="signin"){
                    
                    $action = $_POST['submit'];
                    $this->Auth::$action($_POST);
                
                }
                
            }
            $url = $_SERVER['REQUEST_URI'] == APP_URL_B . "/auth/register"  ? APP_URL_B . "/auth/register" : APP_URL_B . "/auth/login"; 
        }

        // Визначення маршруту та обробка запиту
        self::$router->route($url);

    }

}

?>