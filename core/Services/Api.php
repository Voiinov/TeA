<?php

namespace Core\Services;

use Core\Database;
use App\Helper\Parser;

class Api
{
    /**
     * @param string $method
     * @param array $options
     */
    public function parseData(string $method, array $options = [])
    {
        return Parser::$method($options['id']);
    }

    public function module(string $module, array $options = [])
    {
        $class = "App\modules\\$module\\Api";
        $method = $options['action'];
        require APP_PATH . "/app/modules/{$module}/Api.php";
        $api = new $class;
//        $action();
        return $api->$method($options);

    }

    public function calendar(string $name, $start = null, $end = null)
    {
        $class = "App\modules\\$module\\Api";
        require APP_PATH . "/app/modules/{$module}/Api.php";
        $api = new $class;
        return $api->$method();
    }

}