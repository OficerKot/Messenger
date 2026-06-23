<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$post_id = $_POST['post_id'] ?? 0;     
$comment_text = trim($_POST['comment'] ?? '');

// ✅ Проверка длины комментария
if (strlen($comment_text) > 5000) {
    echo json_encode(['success' => false, 'error' => 'Комментарий слишком длинный (максимум 5000 символов)']);
    exit();
}
$author_id = $_SESSION['id'];

if (empty($comment_text)) {
    echo json_encode(['success' => false, 'error' => 'Пустой комментарий']);
    exit;
}

$comment = new Comment($db);
$comment_id = $comment->createComment($post_id, $comment_text, $author_id);

if ($comment_id) {
    $user = User::getUserById($author_id, $db);
	
    echo json_encode([
        'success' => true,
        'comment' => [
            'comment_id' => $comment_id,
            'post_id' => $post_id,
            'author_id' => $author_id,
            'author_first_name' => $user->get(UserField::FIRST_NAME),
            'author_last_name' => $user->get(UserField::LAST_NAME),
            'author_avatar' => $user->get(UserField::AVATAR),
            'comment' => $comment_text,
            'date' => date('Y-m-d H:i:s'),
            'can_edit' => true,
            'can_delete' => true
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось добавить комментарий']);
}