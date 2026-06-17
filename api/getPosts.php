<?php
header('Content-Type: application/json');
require_once '../classes/Post.php';

$postModel = new Post();

if (isset($_GET['user_id'])) {
    $posts = $postModel->getUserPosts($_GET['user_id']); //стена
} else {
    $posts = $postModel->getNewestPosts(); //общая лента
}

echo json_encode($posts);
?>