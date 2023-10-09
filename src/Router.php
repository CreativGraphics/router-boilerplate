<?php

namespace App;

use Exception;

class Router
{
    private array $routes = [];
    private string $uri;
    private string $currentRoute;
    private array $params = [];

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

            $pattern = "/{[A-z0-9_-]+}/";

            if(preg_match($pattern, $route['route'])){
                if($this->testRoute($route['route'], $this->uri) || $this->testRoute($route['route'] . "/", $this->uri) || $this->testRoute($route['route'], $this->uri . "/")){
                    if (
                        (str_ends_with($route['route'], '/') && !str_ends_with($this->uri, '/')) ||
                        (!str_ends_with($route['route'], '/') && str_ends_with($this->uri, '/'))
                    ) {
                        header("Location: " . $this->link($routeName, $this->params));
                        return;
                    }

                    $this->currentRoute = $routeName;
                    $this->render($route);
                    return;
                }
            }

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

    private function testRoute($route, $url): bool
    {
        $pattern = "/{[A-z0-9_-]+}/";

        $matches = [];

        $keys = [];

        if(preg_match_all($pattern, $route, $matches)){
            foreach ($matches[0] as $key => $value) {
                $keys[$key] = substr($value, 1, -1);
            }
        }

        $pattern2 = "/^".str_replace('/', '\/', preg_replace($pattern, '([^/]+)', $route))."$/i";

        $matches2 = [];

        $result = preg_match($pattern2, $url, $matches2);

        if($result){
            array_shift($matches2);
            foreach ($matches2 as $key => $value) {
                $this->params[$keys[$key]] = urldecode($value);
            }
        }

        return $result;
    }

    private function render($route): void
    {
        require BASE_DIR . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $route['view'];
    }

    public function link($name, $parameters = []): ?string
    {
        foreach ($this->routes as $routeName => $route) {
            if($routeName == $name) {

                $finalRoute = $route['route'];

                $firstParam = true;

                if(sizeof($parameters) > 0){
                    foreach ($parameters as $key => $value) {
                        if(str_contains($finalRoute, "{".$key."}")){
                            $finalRoute = str_replace("{".$key."}", $value, $finalRoute);
                        } else {
                            $finalRoute .= ($firstParam ? "?" : "&") . $key . "=" . urlencode($value);
                            $firstParam = false;
                        }
                    }
                }

                return $finalRoute;
            }
        }

        return null;
    }

    public function getParameter($name): ?string
    {
        if(array_key_exists($name, $this->params)){
            return $this->params[$name];
        }
        return null;
    }

    public function getCurrentRoute(): ?string
    {
        return $this->currentRoute;
    }
}