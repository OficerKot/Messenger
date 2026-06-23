<?php
header('Content-Type: application/json');

require_once '../../includes/init.php';


$msg = $_POST['message'];
$other_user_id = $_POST['other_user_id'];
$sender_id = $_SESSION['id'];
$sender = User::getUserById($sender_id, $db);

$image_path = null;
if (isset($_FILES['image_path']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filename = uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $filename);
    $image_path = $filename;
}

$messageModel = new Message($db);
$result = $messageModel->sendMessage($sender_id, $other_user_id, $msg, $image_path);

if($result){
    
    $notificationManager = new NotificationManager($db, $other_user_id);
    
    $result_notification = $notificationManager->create(
        $other_user_id,
        $sender_id,
        'message',
        $msg
    );

	 echo json_encode([
        'success' => true,
        'message' => [
			'message_id' => $result,
            'message' => $msg,
            'image_path' => $image_path,
            'sender_first_name' => $sender->get(UserField::FIRST_NAME),
            'sender_last_name' => $sender->get(UserField::LAST_NAME),
			'sender_avatar' => $sender->get(UserField::AVATAR),
			'sender_id' => $sender_id,     
            'reciever_id' => $other_user_id,
            'date' => date('Y-m-d H:i:s')
        ]
    ]);
} 
else {
    echo json_encode(['success' => false, 'error' => 'Не удалось отправить сообщение']);
}