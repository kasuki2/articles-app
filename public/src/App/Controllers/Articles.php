<?php

namespace App\Controllers;

class Articles
{
    public function index()
    {
        if (!isset($_SESSION["loggedin"])) {
            header('location: ' . SITEURL . "login");
        } 
        require "views/articles.php";
    }
}

