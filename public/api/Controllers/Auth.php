<?php

namespace Controllers;

use Models\User;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\SessionHelp;

class Auth
{
    private $method;
    private $request;

    public function __construct($method) 
    {
       $this->method = $method;

       $json = file_get_contents('php://input');
       $request = new ReadRequest($json);
       if (!$request) {
            ResponseWriter::sendJsonResponse(500, "failed", "unknown error");
       }
       $this->request = $request->data;
      
        switch ($this->method) {
            case "POST":
            $this->loginUser();
            break;
            case "GET":
            $this->getUser();
            default:
            $this->getUser();
           
        }
    } 

     /**
     * Check if user exists and verifies password to log user in.
     *            
     * @return void
     */
    private function loginUser(): void
    {
        $user = new User();
        // does user exist? 
        $userExists = $user->userExists($this->request["email"]);
        
        if (!$userExists) {
            ResponseWriter::sendJsonResponse(400, "failed", "you are not registered on this website");
        } 

        $pwOk = password_verify($this->request["password"], $userExists->password);

        if (!$pwOk) {
            ResponseWriter::sendJsonResponse(400, "failed", "invalid password");
        }

        $sess = new SessionHelp();
        $sess->createSession($userExists);
        ResponseWriter::sendJsonResponse(200, "success", "successful login");
    }

}   