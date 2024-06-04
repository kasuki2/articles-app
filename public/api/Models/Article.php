<?php

namespace Models;
use Models\Database;

class Article 
{

    private $db;
    
    public function __construct() 
    {
        $this->db = new Database;
    }    
     

    /**
     * Save article in database.
     *
     * @param array     $data       
     * @param int      $userId     
     *
     * @return bool
     */
    public function saveArticle(array $data, int $userId) {
        
       try {
        
            $this->db->query('INSERT INTO articles (user_id, url, title, image_url, description) VALUES (:user_id, :url, :title, :image_url, :description)');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':url', $data["article_url"]);
            $this->db->bind(':title', $data["title"]);
            $this->db->bind(':image_url', $data["image_url"]);
            $this->db->bind(':description', $data["description"]);
            
            $this->db->execute();
            return true;

       } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
       }
    }

    /**
     * Get all the articles belonging to a user.
     *
     * @param int       $userId       
     * @param string     $page     
     * @param int       $limit     
     *
     * @return array
     */
    public function getArticles($userId, $page, $limit): array {

        if ($page) {
            $this->db->query("SELECT * FROM articles WHERE user_id = :user_id LIMIT :limit OFFSET :offset");
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', (int)$page);
        } else {
            $this->db->query("SELECT * FROM articles WHERE user_id = :user_id LIMIT :limit");
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
        }  
        return $this->db->resultSet();
    }

    /**
     * Retrieves articles from db based on search term.
     *
     * @param int       $userId       
     * @param string    $searchTerm         
     *
     * @return array
     */
    public function search($userId, $searchTerm): array {
       
        $this->db->query("SELECT * FROM articles WHERE user_id = :user_id AND title LIKE :title OR description LIKE :description");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', "%" . $searchTerm ."%");
        $this->db->bind(':description', "%" . $searchTerm ."%");
        return $this->db->resultSet();
    }

    /**
     * Counts the articles belongint to a user.
     *
     * @param int       $userId              
     *
     * @return int
     */
    public function countArticles($userId): int {

        $this->db->query("SELECT COUNT(*) AS row_count FROM articles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $res = $this->db->resultSet();
        
        return isset($res[0]) ? $res[0]->row_count : 0;
    }

    /**
     * Deletes an article.
     *
     * @param int       $userId              
     * @param int       $articleId              
     *
     * @return bool
     */
    public function deleteArticle($userId, $articleId): bool {

        try {
        
            $this->db->query("DELETE FROM articles WHERE id = :id AND user_id = :user_id");        
            $this->db->bind(':id', $articleId);
            $this->db->bind(':user_id', $userId); 
            $this->db->execute();
            return true;

       } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
       }

        
    }
}
