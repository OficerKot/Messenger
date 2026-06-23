<?php 
class MessageField {
    const MESSAGE_ID = 'message_id';
    const SENDER_ID = 'sender_id';
    const RECEIVER_ID = 'receiver_id';
    const MESSAGE = 'message';
    const IMAGE_PATH = 'image_path';
    const DATE = 'date';
	
	const IS_READ = 'is_read';
}

class Message {
    private $db;  
    
    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Получить диалог между двумя пользователями
     */
    public function getDialog($user1_id, $user2_id) {
        $sql = "SELECT 
                    m.*,
                    u1." . UserField::FIRST_NAME . " as sender_first_name,
                    u1." . UserField::LAST_NAME . " as sender_last_name,
                    u1." . UserField::AVATAR . " as sender_avatar,
                    u2." . UserField::FIRST_NAME . " as receiver_first_name,
                    u2." . UserField::LAST_NAME . " as receiver_last_name,
                    u2." . UserField::AVATAR . " as receiver_avatar
                FROM direct_messages m
                JOIN users u1 ON m." . MessageField::SENDER_ID . " = u1." . UserField::ID . "
                JOIN users u2 ON m." . MessageField::RECEIVER_ID . " = u2." . UserField::ID . "
                WHERE (m." . MessageField::SENDER_ID . " = ? AND m." . MessageField::RECEIVER_ID . " = ?)
                   OR (m." . MessageField::SENDER_ID . " = ? AND m." . MessageField::RECEIVER_ID . " = ?)
                ORDER BY m." . MessageField::DATE . " ASC";
        
        return $this->db->fetchAll($sql, [$user1_id, $user2_id, $user2_id, $user1_id]);
    }


    /**
     * Получить одно сообщение по ID
     */
    public function getMessageInfo($message_id) {
        $sql = "SELECT 
                    m.*,
                    u1." . UserField::FIRST_NAME . " as sender_first_name,
                    u1." . UserField::LAST_NAME . " as sender_last_name,
                    u1." . UserField::AVATAR . " as sender_avatar
                FROM direct_messages m
                JOIN users u1 ON m." . MessageField::SENDER_ID . " = u1." . UserField::ID . "
                WHERE m." . MessageField::MESSAGE_ID . " = ?";
        
        return $this->db->fetchOne($sql, [$message_id]);
    }

    /**
     * Отправить сообщение
     */
    public function sendMessage($sender_id, $receiver_id, $message, $image_path = null) {
        $data = [
            MessageField::SENDER_ID => $sender_id,
            MessageField::RECEIVER_ID => $receiver_id,
            MessageField::MESSAGE => $message,
            MessageField::IMAGE_PATH => $image_path,
            MessageField::DATE => date('Y-m-d H:i:s'),
			MessageField::IS_READ => 0
        ];
        return $this->db->insert('direct_messages', $data);
    }

    /**
     * Обновить сообщение
     */
    public function editMessage($message_id, $message, $image_path = null) {
        $new_data = [
            MessageField::MESSAGE => $message,
        ];
        
        if ($image_path !== null) {
            $new_data[MessageField::IMAGE_PATH] = $image_path;
        }
        
        return $this->db->update('direct_messages', $new_data, MessageField::MESSAGE_ID . ' = ?', [$message_id]);
    }

    /**
     * Удалить сообщение
     */
    public function deleteMessage($message_id) {
        return $this->db->delete('direct_messages', MessageField::MESSAGE_ID . ' = ?', [$message_id]);
    }
public function markMessagesAsRead($message_ids, $cur_user_id) {
    if (empty($message_ids)) {
        return 0;
    }
    
    $placeholders = implode(',', array_fill(0, count($message_ids), '?'));
    $params = array_merge($message_ids, [$cur_user_id]);
    
    $where = MessageField::MESSAGE_ID . " IN ($placeholders) 
            AND " . MessageField::RECEIVER_ID . " = ?";
    $data = [MessageField::IS_READ => 1];
    
    return $this->db->update('direct_messages',$data, $where, $params);
}

}
?>