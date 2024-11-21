<?php

namespace app\controllers;

use app\models\Posts;

require_once "../app/models/Posts.php"; 

class PostsController
{
    public function validatePost($inputData) {
        $errors = [];
        $title = $inputData['title'];
        $content = $inputData['content'];

        if ($title) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($title) < 5) {
                $errors['titleShort'] = 'Title is too short';
            }
        } else {
            $errors['requiredTitle'] = 'Title is required';
        }

        if ($content) {
            $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($content) < 10) {
                $errors['contentShort'] = 'Content is too short';
            }
        } else {
            $errors['requiredContent'] = 'Content is required';
        }

        if (count($errors)) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }

        return [
            'title' => $title,
            'content' => $content,
        ];
    }

    public function getAllPosts() {
        $id = null;
        $title = '';
        $content = '';
        $created_at = null;
        $updated_at = null;
        $postModel = new Posts($id, $title, $content, $created_at, $updated_at);
        header("Content-Type: application/json");
        $posts = $postModel->getAllPosts();
        echo json_encode($posts);
        exit();
    }

    public function getPostByID($id) {
        if (!$id) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            exit();
        }

        $postModel = new Posts();
        header("Content-Type: application/json");
        $post = $postModel->getPostById($id);
        
        if ($post) {
            echo json_encode($post);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
        }
        exit();
    }

    
    public function savePost() {
        $inputData = [
            'title' => $_POST['title'] ? $_POST['title'] : false,
            'content' => $_POST['content'] ? $_POST['content'] : false,
        ];
        $postData = $this->validatePost($inputData);

        $id = null; 
        $title = $postData['title'];
        $content = $postData['content'];
        $created_at = date('Y-m-d H:i:s'); 
        $updated_at = null;

        $post = new Posts($id, $title, $content, $created_at, $updated_at);
        $post->savePost(
            [
                'title' => $postData['title'],
                'content' => $postData['content'],
            ]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function updatePost($id) {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);

        $inputData = [
            'title' => $_PUT['title'] ? $_PUT['title'] : false,
            'content' => $_PUT['content'] ? $_PUT['content'] : false,
        ];
        $postData = $this->validatePost($inputData);

        $post = new Posts($id);
        $post->updatePost(
            [
                'id' => $id,
                'title' => $postData['title'],
                'content' => $postData['content'],
            ]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function deletePost($id) {
        if (!$id) {
            http_response_code(400); 
            echo json_encode(['error' => 'Post ID is required']);
            exit();
        }
    
        $post = new Posts($id, null, null, null, null); 
        if ($post->deletePost(['id' => $id])) {
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete post']);
        }
        exit();
    }

    public function postsView() {
        include '../public/assets/views/posts/posts-view.html';
        exit();
    }

    public function postsAddView() {
        include '../public/assets/views/posts/posts-add.html';
        exit();
    }

    public function postsDeleteView() {
        include '../public/assets/views/posts/posts-delete.html';
        exit();
    }

    public function postsUpdateView() {
        include '../public/assets/views/posts/posts-update.html';
        exit();
    }
}

?>
