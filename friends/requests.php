<?php

header('Content-Type: application/json');
include '../includes/init.php';
include '../classes/NotificationManager.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}


$sender_id = $_SESSION['id'];
$receiver_id = intval($_POST['user_id']);

// Используем класс User вместо прямых запросов
$current_user = User::getUserById($sender_id, $db);

if (!$current_user) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit();
}

// Отправляем заявку через метод класса User
$result = $current_user->sendFriendRequest($receiver_id);

if ($result['success']) {
    // Создаём уведомление для получателя
    $notificationManager = new NotificationManager($db, $receiver_id);
    $notificationManager->create(
        $receiver_id,
        $sender_id,
        'friend_request',
        'отправил(а) вам заявку в друзья'
    );
}

echo json_encode($result);

?>