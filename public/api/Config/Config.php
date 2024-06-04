<?php

namespace Config;

class Config 
{

    function __construct() {
        
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'articles');
        define('DB_USER', 'articles');
        define('DB_PASSWORD', 'articles');
    }

    
}
