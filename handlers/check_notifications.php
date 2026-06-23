<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'count' => 0]);
    exit();
}

$user_id = $_SESSION['id'];
$last_id = isset($_POST['last_id']) ? intval($_POST['last_id']) : 0;

// Считаем непрочитанные
$sql = "SELECT COUNT(*) as count FROM notifications 
        WHERE receiver_id = ? AND is_read = 0";
$result = $db->fetchOne($sql, [$user_id]);
$unread_count = $result ? $result['count'] : 0;

// Проверяем новые уведомления (с ID больше last_id)
$new_sql = "SELECT COUNT(*) as count FROM notifications 
            WHERE receiver_id = ? AND notification_id > ?";
$new_result = $db->fetchOne($new_sql, [$user_id, $last_id]);
$has_new = $new_result && $new_result['count'] > 0;

// Получаем последний ID
$last_sql = "SELECT MAX(notification_id) as last FROM notifications 
             WHERE receiver_id = ?";
$last_result = $db->fetchOne($last_sql, [$user_id]);
$new_last_id = $last_result ? $last_result['last'] : $last_id;

echo json_encode([
    'success' => true,
    'has_new' => $has_new,
    'unread_count' => $unread_count,
    'last_id' => $new_last_id
]);
?>