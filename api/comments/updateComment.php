<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/init.php';
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$comment_id = $_POST['comment_id'] ?? 0;
$comment_text = $_POST['comment'] ?? '';
$current_user_id = $_SESSION['id'];

$comment = new Comment($db);
$existing = $comment->getCommentInfo($comment_id);

if (!$existing) {
    echo json_encode(['success' => false, 'error' => 'Комментарий не найден']);
    exit;
}

if ($existing['author_id'] != $current_user_id) {
    echo json_encode(['success' => false, 'error' => 'Нет прав']);
    exit;
}

$result = $comment->updateComment($comment_id, $comment_text);

if ($result) {
    echo json_encode([
        'success' => true,
        'comment' => [
            'comment_id' => $comment_id,
            'comment' => $comment_text,
            'author_first_name' => $existing['author_first_name'],
            'author_last_name' => $existing['author_last_name'],
            'author_avatar' => $existing['author_avatar'],
            'date' => $existing['date']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось обновить']);
}