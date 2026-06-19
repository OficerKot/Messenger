<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';

$post_id = $_GET['post_id'] ?? 0;

$comm = new Comment($db);
$comments = $comm->getPostComments($post_id);

$current_user_id = $_SESSION['id'] ?? null;
$isAdmin = User::getUserById($current_user_id, $db)->isAdmin();

foreach ($comments as &$c) {
    $c['can_edit'] = ($current_user_id && $c['author_id'] == $current_user_id);
    $c['can_delete'] = ($current_user_id && ($c['author_id'] == $current_user_id || $isAdmin));
}

echo json_encode($comments);