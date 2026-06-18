<?php 
require_once 'DataBase.php'; 
require_once 'User.php'; 

class PostField {

    public const AUTHOR_ID = 'autor_id';
    public const OWNER_ID = 'wall_owner_id';
    public const MSG = 'message';
    public const IMG = 'image_path';
    public const DATE = 'date';
    public const POST_ID = 'post_id';
}

class Post{
	 private $db;  
    
    public function __construct() {
        $this->db = new Database();
    }
public function getUserPosts($wall_owner_id){
    $sql = "SELECT 
                p.*,
                  u." . UserField::FIRST_NAME . " as author_first_name,
                u." . UserField::LAST_NAME . " as author_last_name,
				u." . UserField::AVATAR . " as author_avatar
            FROM posts p
            JOIN users u ON p." . PostField::AUTHOR_ID . " = u." . UserField::ID . "
            WHERE p." . PostField::OWNER_ID . " = ?
            ORDER BY p." . PostField::DATE . " DESC";
    
    return $this->db->fetchAll($sql, [$wall_owner_id]);
}

public function getNewestPosts(){
    $sql = "SELECT 
                p.*,
                u." . UserField::FIRST_NAME . " as author_first_name,
                u." . UserField::LAST_NAME . " as author_last_name,
				u." . UserField::AVATAR . " as author_avatar
            FROM posts p
            JOIN users u ON p." . PostField::AUTHOR_ID . " = u." . UserField::ID . "
            ORDER BY p." . PostField::DATE . " DESC";
    
    return $this->db->fetchAll($sql);
}

public function getPostInfo($postId){
    $sql = "SELECT 
                p.*,
                u." . UserField::FIRST_NAME . " as author_first_name,
                u." . UserField::LAST_NAME . " as author_last_name,
				u." . UserField::AVATAR . " as author_avatar,
            FROM posts p
            JOIN users u ON p." . PostField::AUTHOR_ID . " = u." . UserField::ID . "
            WHERE p." . PostField::POST_ID . " = ?";
    
    $resArr = $this->db->fetchOne($sql, [$postId]);
    return $resArr;
}

	public function createPost($autor_id, $wall_owner_id, $message, $image_path)
	{
		 $sql = "INSERT INTO posts (
                    " . PostField::POST_ID . ",
                    " . PostField::AUTHOR_ID . ",
                    " . PostField::OWNER_ID . ",
                    " . PostField::MSG . ",
                    " . PostField::IMG . ",
                    " . PostField::DATE . "
                ) VALUES (
                    NULL, ?, ?, ?, ?, NOW()
                )";

		$params = [$autor_id, $wall_owner_id, $message, $image_path];
        
        return $this->db->query1($sql, $params);
	}
	
}
?>