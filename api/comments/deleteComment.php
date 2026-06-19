<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$comment_id = $_POST['comment_id'] ?? 0;
$user_id = $_SESSION['id'];

$comment = new Comment($db);

// Поиск
$res = $comment->getCommentInfo($comment_id);
if (!$res) {
    echo json_encode(['success' => false, 'error' => 'Комментарий не найден']);
    exit;
}

$current_user = User::getUserById($user_id, $db);
$is_admin = $current_user && $current_user->isAdmin();

if ($res['author_id'] != $user_id && !$is_admin) {
    echo json_encode(['success' => false, 'error' => 'Нет прав на удаление']);
    exit;
}

// Удаление
$result = $comment->deleteComment($comment_id); 

echo json_encode(['success' => $result > 0]);