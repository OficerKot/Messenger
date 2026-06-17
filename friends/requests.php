<?php
session_start();
header('Content-Type: application/json');
include '../includes/connectDB.php';
include '../classes/User.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$current_user = new User($_SESSION['id'], $conn);
$receiver_id = intval($_POST['user_id']);

//ОТПРАВИТЬ ФРЕНД-РЕКВЕСТ
$result = $current_user->sendFriendRequest($receiver_id, $conn);
echo json_encode($result);

mysqli_close($conn);
?>