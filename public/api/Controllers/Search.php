<?php

namespace Controllers;

use Models\User;
use Models\Article;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\SessionHelp;

class Search
{
    private $method;
    private $request;
    
    private $searchTerm = "";
   
  
    
    public function __construct($method, $page = null, $searchTerm = null) 
    {

        $sess = new SessionHelp;
        if (!$sess->isLoggedIn()) {
            ResponseWriter::sendJsonResponse(401, "error", "Unauthorized");
        } 

        $this->method = $method;    
        
        if ($this->method != "POST") {
            ResponseWriter::sendJsonResponse(400, "failed", "bad request");
        }

        if ($searchTerm) {
            $this->searchTerm = $searchTerm;
        }

        $json = file_get_contents('php://input');
        $request = new ReadRequest($json);
        if (!$request) {
            ResponseWriter::sendJsonResponse(500, "error", "unknown error");
        }
        
        $this->search();
    }

     /**
     * Filters out a user's articles based on search terms.
     *            
     * @return void
     */

    private function search(): void 
    {
        $sess = new SessionHelp();
        $userId = $sess->getUserId();
        if (!$userId) {
            ResponseWriter::sendJsonResponse(401, "failes", "error saving article, no user id");
        }
        
        $searchResult = [];
        if ($this->searchTerm) {
            $article = new Article();
            $searchResult = $article->search($userId, $this->searchTerm);

            if ($searchResult) {
                $truncate = new ResponseWriter();
    
                foreach($searchResult as $article) {
                    $article->description = $truncate->truncateString($article->description, 120);
                }
            }
        }
        ResponseWriter::sendJsonResponse(200, "success", "searching", $searchResult);
    }
}