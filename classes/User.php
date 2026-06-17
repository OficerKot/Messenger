<?php

class UserField {

    public const AVATAR = 'avatar';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const BIRTHDAY = 'birthday_date';
    public const LOGIN = 'login';
    public const ID = 'user_id';
    public const PRIVATE = 'is_private';
    public const PASSWORD = 'password';
}

class User {
	
    private $data = [];
    private $id;
    private $editable = [
        UserField::FIRST_NAME, 
        UserField::LAST_NAME, 
        UserField::BIRTHDAY, 
        UserField::AVATAR,
        UserField::PRIVATE
    ];
    
    public function __construct($user_id, $conn) {
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $conn->query($sql);
        $this->id = $user_id;
        $this->data = $result->fetch_assoc();
    }
    
    public function get($field) {
        return $this->data[$field] ?? '';
    }
    
    public function getEditableFields() {
        $fields = [];
        foreach ($this->editable as $field) {
            $fields[$field] = $this->get($field);
        }
        return $fields;
    }
    
    public function update($data, $conn) {
        $updates = [];
        foreach ($this->editable as $field) {
            if (isset($data[$field])) {
                $safeValue = $conn->real_escape_string($data[$field]);
                $updates[] = "$field = '$safeValue'";
            }
        }
        
        if (!empty($updates)) {
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = $this->id";
            return $conn->query($sql);
        }
        return true; 
    }

    // РАБОТА С ФУНКЦИЕЙ ДОБАВЛЕНИЯ В ДРУЗЬЯ //

    //ТЕКУЩИЙ СТАТУС ДРУЖБЫ
    public function getFriendshipStatus($other_user_id, $conn) {
        $query = "SELECT status FROM friends 
                  WHERE (user_id = $this->id AND friend_id = $other_user_id) 
                     OR (user_id = $other_user_id AND friend_id = $this->id)";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['status']; // 'pending', 'accepted', 'rejected'
        }
        return 'none'; // нет заявки
    }
    
    //ОТПРАВКА ФРЕНД РЕКВЕСТА
    public function sendFriendRequest($receiver_id, $conn) {
        // Проверяем, нет ли уже заявки
        $status = $this->getFriendshipStatus($receiver_id, $conn);
        
        if ($status !== 'none') {
            return ['success' => false, 'message' => 'Заявка уже существует'];
        }
        
        if ($this->id == $receiver_id) {
            return ['success' => false, 'message' => 'Нельзя добавить самого себя'];
        }
        
        $query = "INSERT INTO friends (user_id, friend_id, status, date) 
                  VALUES ($this->id, $receiver_id, 'pending', NOW())";
        
        if ($conn->query($query)) {
            return ['success' => true, 'message' => 'Заявка отправлена'];
        }
        
        return ['success' => false, 'message' => 'Ошибка базы данных'];
    }
    
    //ДОСТАТЬ СПИСОК ВСЕХ ЮЗЕРОВ КРОМЕ ТЕКУЩЕГО
    public static function getAllUsersExcept($user_id, $conn) {
        $query = "SELECT user_id, first_name, last_name, login 
                  FROM users 
                  WHERE user_id != $user_id";
        $result = $conn->query($query);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

}
?>