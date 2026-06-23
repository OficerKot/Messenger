<?php
header('Content-Type: application/json');
require_once '../../includes/init.php';

$last_message_id = $_POST['last_id'];
$other_user_id = $_POST['other_user_id'];


if(isset($_SESSION['id'])){
	$current_user_id = $_SESSION['id'];
}
else{
	echo json_encode(['error' => 'Не авторизован ', 'non-authorized' => true]);
    exit;
}


$sql = "SELECT 
            m.*,
            u1." . UserField::FIRST_NAME . " as sender_first_name,
            u1." . UserField::LAST_NAME . " as sender_last_name,
            u1." . UserField::AVATAR . " as sender_avatar
        FROM direct_messages m
        JOIN users u1 ON m." . MessageField::SENDER_ID . " = u1." . UserField::ID . "
        WHERE m." . MessageField::MESSAGE_ID . " > ?
            AND ((m." . MessageField::SENDER_ID . " = ? AND m." . MessageField::RECEIVER_ID . " = ?)
               OR (m." . MessageField::SENDER_ID . " = ? AND m." . MessageField::RECEIVER_ID . " = ?))
        ORDER BY m." . MessageField::DATE . " ASC";

$messages = $db->fetchAll($sql, [
    $last_message_id,
    $current_user_id, $other_user_id,
    $other_user_id, $current_user_id
]);

$new_last_id = $last_message_id;
$message_ids = [];

foreach ($messages as &$msg) {
    $msg['is_author'] = ($current_user_id == $msg['sender_id']);
	
	if ($msg['message_id'] > $new_last_id) {
        $new_last_id = $msg['message_id'];
		$message_ids[] = $msg['message_id'];
    }
}

if (!empty($message_ids)) {
    $messageModel = new Message($db);
    $messageModel->markMessagesAsRead($message_ids, $current_user_id);
}

echo json_encode([
    'success' => true,
    'messages' => $messages,
    'new_last_id' => $new_last_id
]);