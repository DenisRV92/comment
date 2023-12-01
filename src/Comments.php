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

    /**
     * @return float|void
     */
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
     * @param $page
     * @return bool|array
     */
    public function getComments($page): bool|array
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

    /**
     * @param $id
     * @return array|false
     */
    public function getCommentById($id): bool|array
    {
        $sql = 'SELECT * FROM comments  WHERE id =:id ';
        $stmt = $this->conn->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @param $formData
     * @return void
     */
    public function handleCommentForm($formData): void
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

    /**
     * @param $title
     * @param $summary
     * @param $body
     * @param $author
     * @return void
     */
    private function createComment($title, $summary, $body, $author): void
    {
        $result = $this->validate($title, $summary, $body, $author);
        if ($result) {
            $sql = "INSERT INTO `comments`(title,summary,body,author) 
                VALUES (:title,:summary,:body,:author)";
            $result = $this->conn->getConnection()->prepare($sql);
            $result->execute(['title' => $title, 'summary' => $summary, 'body' => $body, 'author' => $author]);
        }
    }

    /**
     * @param $id
     * @param $title
     * @param $summary
     * @param $body
     * @param $author
     * @return void
     */
    private function updateComment($id, $title, $summary, $body, $author): void
    {
        $result = $this->validate($title, $summary, $body, $author);
        if ($result) {
            $sql = "UPDATE comments SET title=:title, body=:body, summary=:summary, author=:author WHERE id=:id";
            $result = $this->conn->getConnection()->prepare($sql);
            $result->execute(['id' => $id, 'title' => $title, 'summary' => $summary, 'body' => $body, 'author' => $author]);
        }
    }

    /**
     * @param $id
     * @return void
     */
    public function showEditForm($id): void
    {
        $comment = $this->getCommentById($id);
        require './view/edit.php';
    }

    /**
     * @return void
     */
    public function showCommentList(): void
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $totalCountPage = $this->getTotalCount();
        $comments = $this->getComments($page);
        require './view/list.php';
    }


    /**
     * @param $title
     * @param $summary
     * @param $body
     * @param $author
     * @return bool|void
     */
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