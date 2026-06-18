<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';
include '../classes/NotificationManager.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$notificationManager = new NotificationManager($db, $_SESSION['id']);
echo json_encode(['count' => $notificationManager->getUnreadCount()]);
?>