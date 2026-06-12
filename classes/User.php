<?php

class UserField {
    // Константы вместо enum
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
    
    // Константы - это строки, передаем просто string
    public function get($field) {
        // $field уже строка, не нужно .value
        return $this->data[$field] ?? '';
    }
    
    public function getEditableFields() {
        $fields = [];
        foreach ($this->editable as $field) {
            // $field - это строка (например 'first_name')
            $fields[$field] = $this->get($field);
        }
        return $fields;
    }
    
    public function update($data, $conn) {
        $updates = [];
        foreach ($this->editable as $field) {
            // $field уже строка, не нужно .value
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
}
?>