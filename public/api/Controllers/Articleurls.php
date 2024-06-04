<?php

namespace Controllers;

use Models\User;
use Helpers\ReadRequest;
use Helpers\ResponseWriter;
use Helpers\SessionHelp;

class Articleurls
{
    private $method;
    private $request;
    private $postedUrl;
    private $htmlContent;
    
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
        }
        $this->postedUrl = trim($this->request["article_url"]);

        
        if (!filter_var(  $this->postedUrl , FILTER_VALIDATE_URL)) {
            ResponseWriter::sendJsonResponse(400, "failed", "invalid url");
        } 
        

        $this->processURL();
    } 

    /**
     * Gets a website as a string and filters out meta tags.
     *           
     *
     * @return void
     */
    private function processURL(): void 
    {
        $ch = curl_init();

        $response_headers = [];

        $header_callback = function($ch, $header) use (&$response_headers) {

            $len = strlen($header);

            $parts = explode(":", $header, 2);

            if (count($parts) < 2) {
                return $len;
            }

            $response_headers[$parts[0]] = trim($parts[1]);

            return $len;
        };


        curl_setopt_array($ch, [
            CURLOPT_URL => $this->postedUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADERFUNCTION => $header_callback,
        ]);

        $this->htmlContent = curl_exec($ch);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $ogImage = $this->extractOgImage();
        $ogDescription = $this->extractOgDescription();
        $ogTitle = $this->extractOgTitle();
        
        if ( empty($ogImage) && empty($ogDescription) && empty($ogTitle) ) {
            ResponseWriter::sendJsonResponse(404, "failed", "no title, image or description was found");
        }

        
        if ($ogTitle) {
            $ogTitle = html_entity_decode($ogTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if ($ogDescription) {
            $ogDescription = html_entity_decode($ogDescription, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        

        ResponseWriter::sendJsonResponse(200, "success", "ok", null, null, $ogTitle, $ogImage, $ogDescription);

    }

    /**
     * Filters out og:image's content from a string.
     *            
     * @return bool
     */
    private function extractOgImage(): null | string {
        $pattern = '/<meta[^>]+property="og:image"[^>]+content="([^"]+)"[^>]*>/i';
        if (preg_match($pattern, $this->htmlContent, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Filters out og:description's content from a string.
     *            
     * @return bool
     */
    private function extractOgDescription(): null | string {
        $pattern = '/<meta[^>]+property="og:description"[^>]+content="([^"]+)"[^>]*>/i';
        if (preg_match($pattern, $this->htmlContent, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Filters out og:title's content from a string.
     *            
     * @return bool
     */
    private function extractOgTitle(): null | string {
        $pattern = '/<meta[^>]+property="og:title"[^>]+content="([^"]+)"[^>]*>/i';
        if (preg_match($pattern, $this->htmlContent, $matches)) {
            return $matches[1];
        }
        return null;
    }

}