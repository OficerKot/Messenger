<?php
require_once '../../includes/init.php';
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}


$msg_id = $_POST['msg_id'] ?? 0;
$message = $_POST['message'] ?? '';
$current_user_id = $_SESSION['id'];

$msgModel = new Message($db);
$msg = $msgModel->getMessageInfo($msg_id);

// Проверка прав
if (!$msg || $msg['sender_id'] != $current_user_id) {
    echo json_encode(['success' => false, 'error' => 'Нет прав']);
    exit;
}

if (isset($_POST['removeImage']) && $_POST['removeImage'] === '1') {
    $image_path = null;
} else {
// Обработка изображения
$image_path = $msg['image_path']; 
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $filename);
    $image_path = $filename;
}

}
// Обновляем
$result = $msgModel->editMessage($msg_id, $message, $image_path);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => [
            'message_id' => $msg_id,
            'message' => $message,
            'image_path' => $image_path,
            'sender_first_name' => $msg['sender_first_name'],
            'sender_last_name' => $msg['sender_last_name'],
            'sender_avatar' => $msg['sender_avatar'],
            'date' => $msg['date']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось обновить']);
}
?>