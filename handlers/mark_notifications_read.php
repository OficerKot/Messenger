<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';
include '../classes/NotificationManager.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$notificationManager = new NotificationManager($db, $_SESSION['id']);

if (isset($_POST['all'])) {
    $success = $notificationManager->markAsRead();
} elseif (isset($_POST['ids'])) {
    $ids = json_decode($_POST['ids'], true);
    $success = $notificationManager->markAsRead($ids);
} else {
    echo json_encode(['success' => false]);
    exit();
}

echo json_encode(['success' => $success]);
?>