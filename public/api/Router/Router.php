<?php

namespace Router;

class Router 
{

    public $data;
    private array $routes = [];

    function __construct() {
        
        $this->data = "valami data";

    }


    public function add(string $path, array $params): void
    {
        $this->routes[] = [
            "path" => $path,
            "params" => $params
        ];
    }

    public function match(string $path): array|bool
    {
        /*
        $pattern = "#^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#";
        var_dump($path);
        if (preg_match($pattern, $path, $matches)) {
            var_dump($matches);
            $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

            return $matches;
        }
        */
        
        foreach ($this->routes as $route) {

            if ($route["path"] === $path) {

                return $route["params"];

            }
        }
        

        return false;
    }

    
}
