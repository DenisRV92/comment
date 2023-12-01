<?php

namespace Src;


use PDO;

class Comments
{
    private $conn;
    private $messagesPerPage = 5;

    public function __construct()
    {
        $this->conn = Db::getInstance();
    }

    public function getTotalCount()
    {
        $result = $this->conn->getConnection()->query("SELECT COUNT(*) as count FROM comments");
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil((int)$row["count"] / $this->messagesPerPage);
            return $totalPages;
        }
        return;
    }

    /**
     * Получаем комментарии
     * @return int
     */
    public function getComments($page)
    {
        $offset = ($page - 1) * $this->messagesPerPage;
        $sql = 'SELECT * FROM comments  LIMIT :offset,:messagesPerPage';
        $stmt = $this->conn->getConnection()->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':messagesPerPage', $this->messagesPerPage, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getCommentById($id)
    {
        $sql = 'SELECT * FROM comments  WHERE id =:id ';
        $stmt = $this->conn->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function handleCommentForm($formData)
    {
        $id = $formData['id'] ?? null;
        $title = $formData['title'];
        $summary = $formData['summary'];
        $body = $formData['body'];
        $author = $formData['author'];

        if ($id) {
            $this->updateComment($id, $title, $summary, $body, $author);
        } else {
            $this->createComment($title, $summary, $body, $author);
        }
    }

    private function createComment($title, $summary, $body, $author)
    {
        $result = $this->validate($title, $summary, $body, $author);
        if ($result) {
            $sql = "INSERT INTO `comments`(title,summary,body,author) 
                VALUES (:title,:summary,:body,:author)";
            $result = $this->conn->getConnection()->prepare($sql);
            return $result->execute(['title' => $title, 'summary' => $summary, 'body' => $body, 'author' => $author]);
        }
    }

    private function updateComment($id, $title, $summary, $body, $author)
    {
        $result = $this->validate($title, $summary, $body, $author);
        if ($result) {
            $sql = "UPDATE comments SET title=:title, body=:body, summary=:summary, author=:author WHERE id=:id";
            $result = $this->conn->getConnection()->prepare($sql);
            return $result->execute(['id' => $id, 'title' => $title, 'summary' => $summary, 'body' => $body, 'author' => $author]);
        }
    }

    public function showEditForm($id)
    {
        $comment = $this->getCommentById($id);
        require './view/edit.php';
    }

    public function showCommentList()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $totalCountPage = $this->getTotalCount();
        $comments = $this->getComments($page);
        require './view/list.php';
    }


    public function validate($title, $summary, $body, $author)
    {
        if (!empty($title) && !empty($summary) && !empty($body) && !empty($author)) {
            setcookie('error', '');
            unset($_COOKIE['error']);
            return true;
        }
        setcookie("error", 'Поля должны быть все заполнены');
        header('Location:/index.php');
    }

}