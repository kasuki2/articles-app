<?php

namespace Models;
use Models\Database;

class User 
{

    private $db;
    
    public function __construct() 
    {
        $this->db = new Database;
    }    
     
    /**
     * Checks if a user is in the users table.
     *
     * @param string       $email                           
     *
     * @return bool | stdClass
     */

    public function userExists($email): bool|\stdClass {
        
        $this->db->query("SELECT * FROM users where email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
       
    }

     /**
     * Saves user's data in the database.
     *
     * @param array       $data                          
     *
     * @return bool
     */
    public function registerUser($data): bool {
        
        $hashedPw = password_hash($data["password"], PASSWORD_DEFAULT);

        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

        $this->db->bind(':name', $data["name"]);
        $this->db->bind(':email', $data["email"]);
        $this->db->bind(':password', $hashedPw);

        return $this->db->execute();

    }

    

}
