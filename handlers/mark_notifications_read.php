<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';


if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['id'];
$notificationManager = new NotificationManager($db, $user_id);

if (isset($_POST['all'])) {
    $success = $notificationManager->markAllAsRead();
} elseif (isset($_POST['ids'])) {
    $ids = json_decode($_POST['ids'], true);
    $success = $notificationManager->markAsRead($ids);
} else {
    echo json_encode(['success' => false]);
    exit();
}

// Получаем новый last_id
$last_sql = "SELECT MAX(notification_id) as last FROM notifications WHERE receiver_id = ?";
$last_result = $db->fetchOne($last_sql, [$user_id]);
$new_last_id = $last_result ? $last_result['last'] : 0;




echo json_encode([
    'success' => $success !== false,
    'last_id' => $new_last_id
]);
?>