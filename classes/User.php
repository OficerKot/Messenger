<?php
include "../includes/init.php";

class UserField {

    public const AVATAR = 'avatar';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const BIRTHDAY = 'birthday_date';
    public const LOGIN = 'login';
    public const ID = 'user_id';
    public const PRIVATE = 'is_private';
    public const PASSWORD = 'password';

	public const ROLE = 'role';
}

class User {
	
    private $data = [];
	private $db;
    private $id;
    private $editable = [
        UserField::FIRST_NAME, 
        UserField::LAST_NAME, 
        UserField::BIRTHDAY, 
        UserField::AVATAR,
        UserField::PRIVATE
    ];
    
    public function __construct(int $user_id, array $data, Database $db) {
        $this->id = $user_id;
		$this->db = $db;
        $this->data = $data;
    }

	public static function getUserById($id, Database $db){
		$sql = "SELECT * FROM users WHERE user_id = ?";
		$data = $db->fetchOne($sql, [$id]);
		if($data==null){
			return null;
		} 
		return new self($id, $data, $db);
	}
    
    public function get($field) {
        return $this->data[$field] ?? '';
    }
    
	public function isAdmin(){
		return $this->data[UserField::ROLE] == 1;
	}
    public function getEditableFields() {
        $fields = [];
        foreach ($this->editable as $field) {
            $fields[$field] = $this->get($field);
        }
        return $fields;
    }
    
    public function update($data) {
        $updateData = [];
        foreach ($this->editable as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (!empty($updateData)) {
             return $this->db->update('users', $updateData, 'user_id = ?', [$this->id]);
        }
        return true; 
    }

	public function getAge(){
		$curDate = new DateTime();
		$birthday_date = new DateTime($this->get(UserField::BIRTHDAY));
		$age = $curDate->diff($birthday_date);
		return $age->y;
	}

	public function getFormattedBirthday(){
		$birthday_date = new DateTime($this->get(UserField::BIRTHDAY));
		return $birthday_date->format('d.m.Y');
	}

    // РАБОТА С ФУНКЦИЕЙ ДОБАВЛЕНИЯ В ДРУЗЬЯ //

    //ТЕКУЩИЙ СТАТУС ДРУЖБЫ
      public function getFriendshipStatus($other_user_id) {
        $sql = "SELECT status FROM friends 
                WHERE (user_id = ? AND friend_id = ?) 
                   OR (user_id = ? AND friend_id = ?)";
        
        $result = $this->db->fetchOne($sql, [
            $this->id, $other_user_id,  
            $other_user_id, $this->id   
        ]);
        
        if ($result) {
            return $result['status']; // 'pending', 'accepted', 'rejected'
        }
        return 'none'; // нет заявки
    }
    
    //ОТПРАВКА ФРЕНД РЕКВЕСТА
 public function sendFriendRequest($receiver_id) {
    $status = $this->getFriendshipStatus($receiver_id);
    
    if ($status !== 'none') {
        return ['success' => false, 'message' => 'Заявка уже существует'];
    }
    
    if ($this->id == $receiver_id) {
        return ['success' => false, 'message' => 'Нельзя добавить самого себя'];
    }
    
    $data = [
        'user_id' => $this->id,
        'friend_id' => $receiver_id,
        'status' => 'pending',
        'date' => date('Y-m-d H:i:s')
    ];
    
    $result = $this->db->insert('friends', $data);
    
    if ($result) {
        return ['success' => true, 'message' => 'Заявка отправлена'];
    }
    
    return ['success' => false, 'message' => 'Ошибка базы данных'];
}
    
    public static function getAllUsersExcept($user_id, $db) {
        $query = "SELECT user_id, first_name, last_name, login 
                  FROM users 
                  WHERE user_id != ?";
        $result = $db->fetchAll($query, [$user_id]);
		return $result;
    }

}
?>