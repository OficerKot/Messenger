<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['id'];
$last_id = isset($_POST['last_id']) ? intval($_POST['last_id']) : 0;

$sql = "SELECT n.*, 
        CONCAT(u.first_name, ' ', u.last_name) as sender_name,
        u.login as sender_login,
        u.avatar as sender_avatar
        FROM notifications n
        JOIN users u ON n.sender_id = u.user_id
        WHERE n.receiver_id = ? AND n.notification_id > ?
        ORDER BY n.created_at DESC";

$notifications = $db->fetchAll($sql, [$user_id, $last_id]);

echo json_encode([
    'success' => true,
    'notifications' => $notifications
]);
?>