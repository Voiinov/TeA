<?php 
namespace App\Modules;

use App\Helper\Builder;
use Core\Views;

Class Journal extends Views{

    private static $instance = null;

    public static function start(){
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return [
            "module"=>self::$instance,
            "title"=>"Журнал"
        ];
    }

    protected static function getContent($page="index"){

        if(!method_exists("Journal",$page))
            $page = "index";

        return self::$page();

    }

    protected static function index(){
        include_once("views/index.php");
    }



}


?>