<?php
// classes/NotificationManager.php

class NotificationManager {
    private $conn;
    private $current_user_id;
    
    public function __construct($conn, $current_user_id) {
        $this->conn = $conn;
        $this->current_user_id = $current_user_id;
    }
    
    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as count FROM notifications 
                  WHERE receiver_id = {$this->current_user_id} AND is_read = 0";
        $result = mysqli_query($this->conn, $query);
        if ($result) {
            return mysqli_fetch_assoc($result)['count'];
        }
        return 0;
    }
    
    public function getRecent($limit = 5) {
        $notifications = [];
        $query = "SELECT n.*, 
                  CONCAT(u.first_name, ' ', u.last_name) as sender_name,
                  u.login as sender_login
                  FROM notifications n
                  JOIN users u ON n.sender_id = u.user_id
                  WHERE n.receiver_id = {$this->current_user_id}
                  ORDER BY n.created_at DESC
                  LIMIT $limit";
        $result = mysqli_query($this->conn, $query);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $notifications[] = $row;
            }
        }
        return $notifications;
    }
    
    public function create($receiver_id, $sender_id, $type, $message) {
        $query = "INSERT INTO notifications (receiver_id, sender_id, type, message, created_at, is_read) 
                  VALUES ($receiver_id, $sender_id, '$type', '$message', NOW(), 0)";
        return mysqli_query($this->conn, $query);
    }
    
    public function markAsRead($notification_ids = null) {
        if ($notification_ids === null) {
            $query = "UPDATE notifications SET is_read = 1 
                      WHERE receiver_id = {$this->current_user_id}";
        } else {
            $ids_str = implode(',', array_map('intval', $notification_ids));
            $query = "UPDATE notifications SET is_read = 1 
                      WHERE notification_id IN ($ids_str) 
                      AND receiver_id = {$this->current_user_id}";
        }
        return mysqli_query($this->conn, $query);
    }
}
?>