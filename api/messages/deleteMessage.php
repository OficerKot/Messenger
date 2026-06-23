<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';


if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$msg_id = $_POST['msg_id'] ?? 0;
$current_user_id = $_SESSION['id'];

$msgModel = new Message($db);
$msg = $msgModel->getMessageInfo($msg_id);

if (!$msg) {
    echo json_encode(['success' => false, 'error' => 'Сообщение не найдено']);
    exit;
}


$current_user = User::getUserById($current_user_id, $db);
$is_author = ($msg['sender_id'] == $current_user_id);


if (!$is_author ) {
    echo json_encode(['success' => false, 'error' => 'Нет прав на удаление']);
    exit;
}

$result = $msgModel->deleteMessage($msg_id);

if ($result) {
	echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось удалить сообщение']);
}
?>