<?php

require_once __DIR__ . '/vendor/autoload.php';

use Src\Db;
use Src\Comments;


$db = new Db();
$db->connect();

$comments = new Comments();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comments->handleCommentForm($_POST);
} elseif (isset($_GET['id'])) {
    $comments->showEditForm($_GET['id']);
    die();
}

$comments->showCommentList();


