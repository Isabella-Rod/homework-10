<?php

class Posts {
    private $id;
    private $title;
    private $content;
    private $created_at;
    private $updated_at;

    public function __construct($id, $title, $content, $created_at, $updated_at) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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
}

?>
