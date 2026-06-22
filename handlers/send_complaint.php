<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$sender_id = $_SESSION['id'];
$target_user_id = intval($_POST['user_id']);
$complain_type_id = intval($_POST['type'] ?? 1);
$additional_message = trim($_POST['message'] ?? '');

// Нельзя жаловаться на себя
if ($sender_id == $target_user_id) {
    echo json_encode(['success' => false, 'message' => 'Нельзя пожаловаться на себя']);
    exit();
}

// Проверяем, существует ли тип жалобы
$type_check = "SELECT complain_type_id FROM complain_types WHERE complain_type_id = ?";
$type_exists = $db->fetchOne($type_check, [$complain_type_id]);
if (!$type_exists) {
    echo json_encode(['success' => false, 'message' => 'Неверный тип жалобы']);
    exit();
}

// Проверяем, не жаловался ли уже пользователь на этого человека
$check_sql = "SELECT * FROM complains 
              WHERE sender_id = ? AND target_user_id = ?";
$existing = $db->fetchOne($check_sql, [$sender_id, $target_user_id]);

if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Вы уже жаловались на этого пользователя']);
    exit();
}

// Создаём жалобу
$data = [
    'sender_id' => $sender_id,
    'target_user_id' => $target_user_id,
    'complain_type_id' => $complain_type_id,
    'additional_message' => $additional_message
];

$result = $db->insert('complains', $data);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Жалоба отправлена']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных']);
}
?>