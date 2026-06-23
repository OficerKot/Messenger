<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$current_user_id = $_SESSION['id'];
$friend_id = intval($_POST['user_id']);

$current_user = User::getUserById($current_user_id, $db);

if (!$current_user) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit();
}

$result = $current_user->removeFriend($friend_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Друг удалён']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при удалении']);
}
?>