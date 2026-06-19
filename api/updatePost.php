<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/init.php';
require_once '../classes/Post.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}


$post_id = $_POST['post_id'] ?? 0;
$message = $_POST['message'] ?? '';
$current_user_id = $_SESSION['id'];

$postModel = new Post($db);
$post = Post::getPostInfo($post_id, $db);

// Проверка прав
if (!$post || $post['autor_id'] != $current_user_id) {
    echo json_encode(['success' => false, 'error' => 'Нет прав']);
    exit;
}

if (isset($_POST['removeImage']) && $_POST['removeImage'] === '1') {
    $image_path = null;
} else {
// Обработка изображения
$image_path = $post['image_path']; 
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $filename);
    $image_path = $filename;
}

}
// Обновляем
$result = $postModel->updatePost($post_id, $message, $image_path);

if ($result) {
    echo json_encode([
        'success' => true,
        'post' => [
            'post_id' => $post_id,
            'message' => $message,
            'image_path' => $image_path,
            'author_first_name' => $post['author_first_name'],
            'author_last_name' => $post['author_last_name'],
            'author_avatar' => $post['author_avatar'],
            'date' => $post['date']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось обновить']);
}
?>