<?php

namespace App;

use App\Template\Template;
use Exception;
use ReflectionClass;

class Router
{
    private array $routes = [];
    private string $uri;
    private string $currentRoute;

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        if(!file_exists(BASE_DIR . "/routes.yml")) {
            throw new Exception('routes.yml not found');
        }

        $this->routes = Spyc::YAMLLoad(BASE_DIR . '/routes.yml');
    }

    public function matchRoute(): void
    {
        foreach ($this->routes as $routeName => $route) {
            if($route['route'] == $this->uri || $route['route'] . "/" == $this->uri || $route['route'] == $this->uri . "/"){
                if (
                    (str_ends_with($route['route'], '/') && !str_ends_with($this->uri, '/')) ||
                    (!str_ends_with($route['route'], '/') && str_ends_with($this->uri, '/'))
                ) {
                    header("Location: " . $route['route']);
                    return;
                }
                $this->currentRoute = $routeName;
                $this->render($route);
                return;
            }
        }

        $this->render(['route' => '404', 'view' => 'error/404.php']);
        http_response_code(404);
    }

    private function render($route): void
    {
        require BASE_DIR . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $route['view'];
    }

    public function link($name): ?string
    {
        foreach ($this->routes as $routeName => $route) {
            if($routeName == $name) {
                return $route['route'];
            }
        }

        return null;
    }

    public function getCurrentRoute(): ?string
    {
        return $this->currentRoute;
    }
}