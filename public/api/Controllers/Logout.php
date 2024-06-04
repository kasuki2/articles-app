<?php

namespace Controllers;

use Models\User;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\SessionHelp;

class Logout
{
    private $method;
    private $request;

    public function __construct($method) 
    {
        $sess = new SessionHelp;
        if (!$sess->isLoggedIn()) {
            ResponseWriter::sendJsonResponse(401, "error", "Unauthorized");
        } 
       $this->method = $method;

       $json = file_get_contents('php://input');
       $request = new ReadRequest($json);
       if (!$request) {
            ResponseWriter::sendJsonResponse(500, "error", "unknown error");
       }

       $this->request = $request->data;
      
       if ($this->method != "POST") {
            ResponseWriter::sendJsonResponse(400, "failed", "bad request");
       } else {
        $this->logUserOut();
       }
    } 

     /**
     * Destroys the user's session to log user out.
     *            
     * @return void
     */

    private function logUserOut(): void
    {
        unset($_SESSION['loggedin']);
        unset($_SESSION['name']);
        session_destroy();
        ResponseWriter::sendJsonResponse(200, "success", "logged out successfully");
    }

}   