<?php
include "../includes/init.php";
class NotificationManager {
    private $db;
    private $current_user_id;
    
    public function __construct(Database $db, $current_user_id) {
        $this->db = $db;
        $this->current_user_id = $current_user_id;
    }
    
    public function getUnreadCount() {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE receiver_id = ? AND is_read = 0";
        $result = $this->db->fetchOne($sql, [$this->current_user_id]);
        return $result ? $result['count'] : 0;
    }

	public function getRecent($limit = 5) {
    $sql = "SELECT n.*, 
            CONCAT(u.first_name, ' ', u.last_name) as sender_name,
            u.login as sender_login
            FROM notifications n
            JOIN users u ON n.sender_id = u.user_id
            WHERE n.receiver_id = ?
            ORDER BY n.created_at DESC
            LIMIT " . (int)$limit;  
    
    return $this->db->fetchAll($sql, [$this->current_user_id]);
}


    
    public function create($receiver_id, $sender_id, $type, $message) {
        $data = [
            'receiver_id' => $receiver_id,
            'sender_id' => $sender_id,
            'type' => $type,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'is_read' => 0
        ];
        return $this->db->insert('notifications', $data);
    }
    
    public function markAsRead($notification_ids = null) {
        if ($notification_ids === null) {
            // Отметить все как прочитанные
            $sql = "UPDATE notifications SET is_read = 1 
                    WHERE receiver_id = ?";
            return $this->db->query1($sql, [$this->current_user_id])->rowCount();
        } else {
            // Отметить конкретные уведомления
            if (empty($notification_ids)) {
                return 0;
            }
            
            $placeholders = implode(',', array_fill(0, count($notification_ids), '?'));
            $params = array_merge($notification_ids, [$this->current_user_id]);
            
            $sql = "UPDATE notifications SET is_read = 1 
                    WHERE notification_id IN ($placeholders) 
                    AND receiver_id = ?";
            return $this->db->query1($sql, $params)->rowCount();
        }
    }
    
    public function markAllAsRead() {
        $sql = "UPDATE notifications SET is_read = 1 
                WHERE receiver_id = ?";
        return $this->db->query1($sql, [$this->current_user_id])->rowCount();
    }
}
?>