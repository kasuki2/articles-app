<?php

namespace Helpers;

class Sanitize 
{

    /**
     * Retrieves articles from db based on search term.
     *
     * @param string            $data         
     *
     * @return string
     */
    public function sanitizeInput($data): string {
        $data = trim($data); 
        $data = stripslashes($data); 
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); 
        return $data;
    } 
 
    
}