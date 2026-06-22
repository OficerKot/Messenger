<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';
$messageModel = new Message($db);


if(isset($_SESSION['id'])){
	$current_user_id = $_SESSION['id'];
}
else{
	echo json_encode(['error' => 'Не авторизован ', 'non-authorized' => true]);
    exit;
}

if(isset($_GET['user_id'])){
	$other_user_id = $_GET['user_id'] ;
}
else{
	echo json_encode(['error' => 'Отсутствует id собеседника ']);
    exit;
}

$messages = $messageModel->getDialog($current_user_id, $other_user_id);
echo json_encode($messages);