<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';
include '../classes/NotificationManager.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$current_user_id = $_SESSION['id'];
$sender_id = intval($_POST['user_id']);

// Проверяем, есть ли заявка
$check_sql = "SELECT * FROM friends 
              WHERE user_id = ? AND friend_id = ? AND status = 'pending'";
$existing = $db->fetchOne($check_sql, [$sender_id, $current_user_id]);

if (!$existing) {
    echo json_encode(['success' => false, 'message' => 'Заявка не найдена']);
    exit();
}

// Обновляем статус на 'accepted'
$update_sql = "UPDATE friends SET status = 'accepted' 
               WHERE user_id = ? AND friend_id = ? AND status = 'pending'";
$result = $db->query1($update_sql, [$sender_id, $current_user_id]);

if ($result->rowCount() > 0) {
    // Создаём уведомление для отправителя
    $notificationManager = new NotificationManager($db, $sender_id);
    $notificationManager->create(
        $sender_id,
        $current_user_id,
        'friend_accept',
        'принял(а) вашу заявку в друзья'
    );
    
    echo json_encode(['success' => true, 'message' => 'Заявка принята']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при принятии заявки']);
}
?>