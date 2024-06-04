<?php

namespace App\Controllers;

class Addarticle
{
    public function index()
    {
        if (!isset($_SESSION["loggedin"])) {
            header('location: ' . SITEURL . "login");
        } 

        require "views/add-article.php";
    }
}

