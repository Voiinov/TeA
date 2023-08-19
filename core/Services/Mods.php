<?php

namespace Core\Services;

use Core\App;

class Mods extends App
{

    private static $appPath = APP_PATH . "/app/modules/";

    /**
     * @param $module
     * @param $options
     * @return mixed
     */
    public static function loadMod($module = "dashboard", $options = [])
    {

        if (!file_exists(self::$appPath . $module . "/loader.php")) {
            $module = "dashboard";
        }

        include_once(self::$appPath . $module . "/loader.php");

        $module = "App\Modules\\" . ucfirst($module);

        return $module::start();
    }

    public static function getContent($module, $options = [])
    {
        echo $module;
    }

    public static function init()
    {
        self::loadPaths();
    }

    private static function loadPaths()
    {
        foreach (self::scan() as $modFolder) {
            App::$router->addRoute("{$modFolder}", "DashboardController", "index", $modFolder);
        }
    }

    private static function scan(): array
    {
        return array_slice(scandir(self::$appPath), 2);
    }

}
