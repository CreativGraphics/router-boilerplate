<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . "/autoload.php";

use App\DotEnv;
use App\Router;

if(file_exists(BASE_DIR . "/.env.local")){
    (new DotEnv(BASE_DIR . "/.env.local"))->load();
}
(new DotEnv(BASE_DIR . "/.env"))->load();

if(getenv('APP_ENV') == "dev") {
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

$router = new Router();
$router->matchRoute();

?>