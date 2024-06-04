<?php

namespace Controllers;

use Models\User;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\Sanitize;
class Users
{
    private $method;
    private $request;

    public function __construct($method) 
    {
        

       $this->method = $method;

       $json = file_get_contents('php://input');
       $request = new ReadRequest($json);
       if (!$request) {

        ResponseWriter::sendJsonResponse(500, "error", "unknown error");
           
       }
       $this->request = $request->data;
      

        switch ($this->method) {
            case "POST":
            $this->registerUser();
            break;
            case "GET":
            $this->getUser();
            default:
            $this->getUser();
           
        }
  
   
    }   

    /**
     * Check registration data and registers users
     *            
     * @return void
     */
    public function registerUser(): void
    {

        $sanitize = new Sanitize();

        $this->request["name"] = $sanitize->sanitizeInput( $this->request["name"]);
        $this->request["password"] = $sanitize->sanitizeInput( $this->request["password"]);
        $this->request["confirm_password"] = $sanitize->sanitizeInput( $this->request["confirm_password"]);
        

        if (empty($this->request["name"])) {
            ResponseWriter::sendJsonResponse(400, "failed", "you need to submit a name, min. 3 characters");
        }

        if (empty($this->request["password"])) {
            ResponseWriter::sendJsonResponse(400, "failed", "you need to submit a password, minimum 8 characters");
        }
        if (empty($this->request["confirm_password"])) {
            ResponseWriter::sendJsonResponse(400, "failed", "you need to confirm the password, the two passwords need to match");
        }


        if (!filter_var($this->request["email"], FILTER_VALIDATE_EMAIL)) {
            ResponseWriter::sendJsonResponse(400, "failed", "you need to submit a valid email address");
        }

            $user = new User();

            if ($user->userExists($this->request["email"]) )
            {
                ResponseWriter::sendJsonResponse(400, "failed", "someone has already registered with this email address");
            }

        if (strlen($this->request["name"]) < 3) {
            ResponseWriter::sendJsonResponse(400, "failed", "the name must be at least 3 characters");
        }
        if (strlen($this->request["name"]) > 100) {
            ResponseWriter::sendJsonResponse(400, "failed", "the name cannot be longer than 100 characters");
        }

        if (strlen($this->request["password"]) < 8) {
            ResponseWriter::sendJsonResponse(400, "failed", "the password must be at leas 8 characters");
        }
        if (strlen($this->request["password"]) > 50) {
            ResponseWriter::sendJsonResponse(400, "failed", "the name cannot be longer than 50 characters");
        }

        if ($this->request["password"] != $this->request["confirm_password"]) {
            ResponseWriter::sendJsonResponse(400, "failed", "the two passwords do not match");
        }

        $registrationResult = $user->registerUser($this->request);

        if ($registrationResult) {
            ResponseWriter::sendJsonResponse(201, "success", "successfull registration");
        } else {
            ResponseWriter::sendJsonResponse(201, "failed", "error saving registration");
        }


      
    }

    

   
}