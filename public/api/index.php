<?php
session_start();


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = ltrim($path, "/");
spl_autoload_register(function (string $class_name) {

    require str_replace("\\", "/", $class_name) . ".php";

});

$method = $_SERVER["REQUEST_METHOD"];

$router = new Router\Router;
$config = new Config\Config;


$router->add("api/auth", ["controller" => "auth"]);

$router->add("api/users", ["controller" => "users"]);
$router->add("api/logout", ["controller" => "logout"]);
$router->add("api/articleurls", ["controller" => "articleurls"]);
$router->add("api/articles", ["controller" => "articles"]);
$router->add("api/search", ["controller" => "search"]);


$params = $router->match($path);

if ($params === false) {
    http_response_code(404);
    exit("No such end point");
    
}
//var_dump($params);
$controller = "Controllers\\" . ucwords($params["controller"]);

$page = null;
if (isset($_GET['page'])) {
    if (ctype_digit($_GET['page'])) {
        $page = $_GET['page'];
    }
}
$searchTerm = null;
if (isset($_GET['s'])) {
    $searchTerm = $_GET['s'];
}

$controller_object = new $controller($method, $page, $searchTerm);

//var_dump($controller_object);
