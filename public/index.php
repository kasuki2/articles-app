<?php
session_start();
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

spl_autoload_register(function (string $class_name) {

    require "src/" . str_replace("\\", "/", $class_name) . ".php";

});

$method = $_SERVER["REQUEST_METHOD"];

$config = new App\Config\Config;
$router = new Framework\Router;


$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/login", ["controller" => "login", "action" => "index"]);
$router->add("/register", ["controller" => "register", "action" => "index"]);
$router->add("/logout", ["controller" => "logout", "action" => "index"]);
$router->add("/articles", ["controller" => "articles", "action" => "index"]);
$router->add("/add-article", ["controller" => "addarticle", "action" => "index"]);

$params = $router->match($path);

if ($params === false) {
    exit("No route matched");
}

$action = $params["action"];

$controller = "App\\Controllers\\" . ucwords($params["controller"]);

$controller_object = new $controller($method);

$controller_object->$action();

?>