<?php

namespace App\Modules;

use Core\Views;

class Profile extends Views
{
    private static $instance = null;

    public static function start()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        $preSets = self::getPageRequest("p");

        return self::$preSets();

    }

    /**
     * @param string $key ключ масиву
     * @return string
     */
    protected static function getPageRequest(string $key): string
    {

        $p = $_GET[$key] ?? "index";

        switch ($p) {
            case("mail"):
                return "mail";
                break;
            default:
                return "index";
        }

    }

    protected static function getContent($page="index"){

        include("views/" . $page . ".php");

    }

    protected static function index()
    {
        return [
            "module"=>self::$instance,
            "title"=>_("Profile"),
            "page"=>"profile",
            "plugins"=>["footer"=>["customJSCode"=>["src"=> "/js/modules/synchronizer.js"]]]
            ];
    }

}