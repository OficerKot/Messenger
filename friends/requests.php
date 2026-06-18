<?php
session_start();
header('Content-Type: application/json');
include '../includes/connectDB.php';
include '../classes/User.php';
include '../classes/NotificationManager.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$sender_id = $_SESSION['id'];
$receiver_id = intval($_POST['user_id']);

// Проверяем, не отправляет ли пользователь заявку сам себе
if ($sender_id == $receiver_id) {
    echo json_encode(['success' => false, 'message' => 'Нельзя отправить заявку самому себе']);
    exit();
}

// Проверяем, нет ли уже заявки или дружбы
$check_query = "SELECT * FROM friends
                WHERE (user_id = $sender_id AND friend_id = $receiver_id) 
                   OR (user_id = $receiver_id AND friend_id = $sender_id)";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $existing = mysqli_fetch_assoc($check_result);
    if ($existing['status'] == 'pending') {
        echo json_encode(['success' => false, 'message' => 'Заявка уже отправлена']);
    } elseif ($existing['status'] == 'accepted') {
        echo json_encode(['success' => false, 'message' => 'Вы уже друзья']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Заявка уже существует']);
    }
    exit();
}

// Добавляем новую заявку
$insert_query = "INSERT INTO friends (user_id, friend_id, status, date) 
                 VALUES ($sender_id, $receiver_id, 'pending', CURDATE())";

if (mysqli_query($conn, $insert_query)) {
    // ===== СОЗДАЁМ УВЕДОМЛЕНИЕ ДЛЯ ПОЛУЧАТЕЛЯ =====
    $notificationManager = new NotificationManager($conn, $receiver_id);
    $notificationManager->create(
        $receiver_id,           // кому
        $sender_id,             // от кого
        'friend_request',       // тип
        'отправил(а) вам заявку в друзья'  // сообщение
    );
    
    echo json_encode(['success' => true, 'message' => 'Заявка отправлена']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>