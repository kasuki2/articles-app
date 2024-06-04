<?php


namespace Helpers;

class SessionHelp 
{

    /**
     * Retrieves articles from db based on search term.
     *
     * @param object            $user         
     *
     */
    public function createSession($user) {

        $_SESSION['name'] = $user->name;
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user->id;

    }

    /**
     * Retrieves articles from db based on search term.
     *  
     *
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION["loggedin"]);
    }    

    /**
     * Retrieves articles from db based on search term.
     *  
     *
     * @return bool | string
     */
    public function getUserId() {
        if (isset($_SESSION["user_id"])) {
            return $_SESSION["user_id"];
        } else {
            return false;
        }
    }
}
