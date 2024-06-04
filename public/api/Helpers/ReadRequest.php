<?php

namespace Helpers;

class ReadRequest 
{

    public $data;

    function __construct($json) {
        
        $data = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $this->data = $data;
            return $this->data;
        } else {
           return $this->data;
        }

    }

    
}
