<?php

namespace Controllers;

use Models\User;
use Models\Article;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\SessionHelp;
use Helpers\Sanitize;

class Articles
{
    private $method;
    private $request;
    private $postedUrl;
    private $htmlContent;
    private $page = null;
    private $itemPerPage = 10;
    
    public function __construct($method, $page = null) 
    {

        $sess = new SessionHelp;
        if (!$sess->isLoggedIn()) {
            ResponseWriter::sendJsonResponse(401, "error", "Unauthorized");
        } 

        $this->method = $method;    

        if ($page) {
            $this->page = $page;
        }

        $json = file_get_contents('php://input');
        $request = new ReadRequest($json);
        if (!$request) {
         ResponseWriter::sendJsonResponse(500, "error", "unknown error");
        }
        
        $this->request = $request->data;
        
        switch ($this->method) {
            case "POST":
            $this->saveArticle();
            break;
            case "DELETE":
            $this->deleteArticle();
            default:
            $this->getArticles();
        }
    } 

    /**
     * Saves the article in the database.
     *                        
     *
     * @return void
     */
    private function saveArticle(): void {

        if (!isset($this->request["article_url"]) ) {
            ResponseWriter::sendJsonResponse(400, "failed", "article must have a link");
        }

        if (empty(trim($this->request["article_url"]))) {
            ResponseWriter::sendJsonResponse(400, "failed", "article must have a link");
        }

        if (!filter_var( $this->request["article_url"] , FILTER_VALIDATE_URL)) {
            ResponseWriter::sendJsonResponse(400, "failed", "invalid url");
        } 

        if (empty($this->request["title"]) || strlen($this->request["title"]) < 3) {
            ResponseWriter::sendJsonResponse(400, "failed", "article title is too short. Please, give a title to the article. Minimum 3 characters are needed");
        }


        if (strlen($this->request["title"]) > 500 ) {
            ResponseWriter::sendJsonResponse(400, "failed", "article title is too long. Maximum 500 characters are allowed");
        }
        if (strlen($this->request["description"]) > 3000 ) {
            ResponseWriter::sendJsonResponse(400, "failed", "the description is too long. Maximum 3000 characters are allowed");
        }
        if (strlen($this->request["image_url"]) > 500 ) {
            ResponseWriter::sendJsonResponse(400, "failed", "the image url is too long. Maximum 500 characters are allowed");
        }

        $sess = new SessionHelp();
        $userId = $sess->getUserId();
        if (!$userId) {
            ResponseWriter::sendJsonResponse(401, "failes", "error saving article, no user id");
        }

        $sanitize = new Sanitize();

        foreach($this->request as &$request) {
            $request = $sanitize->sanitizeInput($request);
        }
        
        $article = new Article();
       
            
            $saveArticle = $article->saveArticle($this->request, $userId);
            if ($saveArticle) {
                ResponseWriter::sendJsonResponse(200, "success", "saving article");
            }    
            
            ResponseWriter::sendJsonResponse(500, "success", "saving article");
           
    }

    /**
     * Gets all the articles a users have.
     *                        
     *
     * @return void
     */
    private function getArticles(): void {
       
        $sess = new SessionHelp();
        $userId = $sess->getUserId();
        if (!$userId) {
            ResponseWriter::sendJsonResponse(401, "failes", "error getting articles, no user id");
        }
        

        $article = new Article();
        $offset = 0;
        if ($this->page) {

            $offset = ($this->page - 1) * $this->itemPerPage;
        }
        $articles = $article->getArticles($userId, $offset, $this->itemPerPage);
        $numberOfArticles = $article->countArticles($userId);

        if ($articles) {
            $truncate = new ResponseWriter();

            foreach($articles as $article) {
                $article->description = $truncate->truncateString($article->description, 120);
            }
        }
        ResponseWriter::sendJsonResponse(200, "success", "getting article", $articles, $numberOfArticles);
    }

     /**
     * Deletes an article.
     *                        
     *
     * @return void
     */
    private function deleteArticle(): void {
       
        $sess = new SessionHelp();
        $userId = $sess->getUserId();
        if (!$userId) {
            ResponseWriter::sendJsonResponse(401, "failes", "error getting articles, no user id");
        }
        
        $article = new Article();
        
        $delete = $article->deleteArticle($userId, $this->request["id"]);
        $delete = true;
        if ($delete) {
            ResponseWriter::sendJsonResponse(204, "success", "deleting article");
        } else {
            ResponseWriter::sendJsonResponse(500, "error", "something went terribly wrong");
        }
        
    }

}