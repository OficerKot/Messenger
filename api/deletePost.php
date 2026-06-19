<?php
header('Content-Type: application/json');

require_once '../classes/Post.php';
require_once '../includes/init.php';


if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$post_id = $_POST['post_id'] ?? 0;
$current_user_id = $_SESSION['id'];

$postModel = new Post($db);
$post = Post::getPostInfo($post_id, $db);

if (!$post) {
    echo json_encode(['success' => false, 'error' => 'Пост не найден']);
    exit;
}


$current_user = User::getUserById($current_user_id, $db);
$is_author = ($post['autor_id'] == $current_user_id);
$is_wall_owner = ($post['wall_owner_id'] == $current_user_id);
$is_admin = $current_user && $current_user->isAdmin();

if (!$is_author && !$is_wall_owner && !$is_admin) {
    echo json_encode(['success' => false, 'error' => 'Нет прав на удаление']);
    exit;
}

$result = $db->delete('posts', 'post_id = ?', [$post_id]);
if ($result) {
	echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось удалить пост']);
}
?>