<?php

namespace app\models; 

use app\models\Model;

class Posts {

    public function getAllPosts() {
        try {
            $db = new \PDO('mysql:host=localhost;dbname=homework-10-main', 'root', 'homework10');
            $sql = "SELECT * FROM posts";
            $stmt = $db->query($sql);
            
            $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($posts) {
                return $posts;
            } else {
                return [];
            }
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Failed to fetch posts.");
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    // Setters
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    public static function fromArray($data) {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? '',
            $data['content'] ?? '',
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function savePost($data) {
        $db = new \PDO('mysql:host=localhost;dbname=homework-10-main', 'root', 'homework10');
        
        $sql = "INSERT INTO posts (title, content, created_at) VALUES (:title, :content, :created_at)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':created_at', $data['created_at']);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId(); 
            return true;
        } else {
            throw new \Exception('Failed to save post.');
        }
    }
    
    public function deletePost($data) {
        $db = new \PDO('mysql:host=localhost;dbname=homework-10-main', 'root', 'homework10');
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $data['id'], \PDO::PARAM_INT);
    
        return $stmt->execute(); 
    }

    public function updatePost($id, $data) {
        $db = new \PDO('mysql:host=localhost;dbname=homework-10-main', 'root', 'homework10');
    
        $sql = "UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id";
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
    
        if ($stmt->execute()) {
            return true;
        } else {
            throw new \Exception('Failed to update post.');
        }
    }

    public function getPostById($id) {
        $db = new \PDO('mysql:host=localhost;dbname=homework-10-main', 'root', 'homework10');
    
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    
        $posts = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        if ($posts) {
            return $posts;
        } else {
            throw new \Exception("Post with ID $id not found.");
        }
    }
    
    
    
}


?>
