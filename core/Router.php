<?php

namespace Core;

use Core\Services\Auth\Permission;

class Router
{
    protected $routes = [];
    protected $controllerNamespace = 'App\Controllers\\';
    protected $defaultController = 'DashboardController';
    protected $defaultMethod = 'index';

    public function addRoute($url, $controller, $method, $module = null)
    {
        $this->routes[$url] = [
            'controllerName' => $controller,
            'methodName' => $method,
            'module' => $module
        ];
    }

    public function route($url)
    {

        $module = "dashboard";

        if (isset($this->routes[$url])) {
            $controllerName = $this->controllerNamespace . $this->routes[$url]['controllerName'];
            $methodName = $this->routes[$url]['methodName'];
            $module = $this->routes[$url]['module'];
        } else {
            // Розбити URL на частини
            $urlParts = explode('/', trim($url, '/'));
            // Визначити контролер і метод для виклику
            $controllerName = ucfirst(array_shift($urlParts));
            $controllerName = $this->controllerNamespace . ($controllerName ? "{$controllerName}Controller" : $this->defaultController);
            $methodName = array_shift($urlParts) ?: $this->defaultMethod;
        }

        // Перевірити, чи існує клас контролера і метод, інакше повернути помилку 404
        if (!class_exists($controllerName) || !method_exists($controllerName, $methodName)) {
            header('HTTP/1.0 404 Not Found');
            echo '<p>404 Not Found</p>';
            return;
        }

        if(!Permission::access( $url )){
            echo '<p>Access denied</p>';
            return;
        }

        // Створити об'єкт контролера і викликати метод
        $controller = new $controllerName($module);
        $controller->$methodName();

    }

}

?>