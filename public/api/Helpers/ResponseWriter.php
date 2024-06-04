<?php

namespace Helpers;

class ResponseWriter 
{

    /**
     * Retrieves articles from db based on search term.
     *
     * @param int               $statusCode       
     * @param string            $status         
     * @param string            $message         
     * @param array             $data         
     * @param int               $count
     * @param string | null     $title
     * @param string | null     $imageurl
     * @param string | null     $description
     * 
     *
     * @return string
     */
    public static function sendJsonResponse($statusCode, $status, $message, $data = [], $count = null, $title = null, $imageurl = null, $description = null): void {
        http_response_code($statusCode);
        $response = new \stdClass();
        $response->status = $status;
        $response->message = $message;
        if ($data) {
            $response->data = $data;
        }
        if ($count) {
            $response->count = $count;
        }
        if ($title) {
            $response->title = $title;
        }
        if ($imageurl) {
            $response->imageurl = $imageurl;
        }
        if ($description) {
            $response->description = $description;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Truncates a string at word boundary.
     *
     * @param string            $string       
     * @param int            $length         
     * @param string            $ellipsis         
     * 
     *
     * @return string
     */
    public function truncateString($string, $length, $ellipsis = '...'): string | null {
        if ($string) {
            if (strlen($string) <= $length) {
                return $string;
            }
            $truncatedString = substr($string, 0, $length);
            $lastSpace = strrpos($truncatedString, ' ');
            if ($lastSpace !== false) {
                $truncatedString = substr($truncatedString, 0, $lastSpace);
            }
        
            return $truncatedString . $ellipsis;
        } 
        
        return null;
    }

}
