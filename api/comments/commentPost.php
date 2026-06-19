<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$comment = $_POST['comment'] ?? '';
$post_id = $_POST['post_id'] ?? 0;
$author_id = $_SESSION['id']; 

if (empty($comment)) {
    echo json_encode(['success' => false, 'error' => 'Комментарий не может быть пустым']);
    exit;
}
$commentModel = new Comment($db);
$result = $commentModel->createComment($post_id, $comment, $author_id);

if ($result) {
    $user = User::getUserById($author_id, $db);

    echo json_encode([
        'success' => true,
        'comment' => [
            'comment_id' => $result,
            'post_id' => $post_id,
            'author_id' => $author_id,
            'author_first_name' => $user->get(UserField::FIRST_NAME),
            'author_last_name' => $user->get(UserField::LAST_NAME),
            'author_avatar' => $user->get(UserField::AVATAR),
            'comment' => $comment,
            'can_edit' => true,
            'can_delete' => true,
            'date' => date('Y-m-d H:i:s')
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось добавить комментарий']);
}
?>