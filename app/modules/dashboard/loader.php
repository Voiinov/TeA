<?php 
namespace App\Modules;

use Core\Views;

Class Dashboard extends Views{

    private static $instance = null;

    public static function start(){
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return [
            "module"=>self::$instance,
            "title"=>"Панель керування"
        ];
        
    }

    protected static function getContent($page="index"){

            if(!method_exists("Dashboard",$page))
                $page = "index";

        return self::$page();

    }

    protected static function index(){
        include_once("views/index.php");
    }


}


?>