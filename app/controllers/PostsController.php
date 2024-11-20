<?php

namespace app\controllers;

use app\models\Posts;

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
        $postModel = new Posts($id, $title, $content, $created_at, $updated_at);
        header("Content-Type: application/json");
        $posts = $postModel->getPostById($id);
        echo json_encode($posts);
        exit();
    }

    public function savePost() {
        $inputData = [
            'title' => $_POST['title'] ? $_POST['title'] : false,
            'content' => $_POST['content'] ? $_POST['content'] : false,
        ];
        $postData = $this->validatePost($inputData);

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

        $post = new Posts($id, $title, $content, $created_at, $updated_at);
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
            http_response_code(404);
            exit();
        }

        $post = new Posts($id, $title, $content, $created_at, $updated_at);
        $post->deletePost(
            [
                'id' => $id,
            ]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
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
