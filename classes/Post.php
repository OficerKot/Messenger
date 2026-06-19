<?php 
include "../includes/init.php";

class PostField {

    public const AUTHOR_ID = 'autor_id';
    public const OWNER_ID = 'wall_owner_id';
    public const MSG = 'message';
    public const IMG = 'image_path';
    public const DATE = 'date';
    public const POST_ID = 'post_id';
}


//Его поидее полностью можно было сделать статическим, но уже ладно
class Post{
	 private $db;  
    
    public function __construct(Database $db) {
        $this->db = $db;
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

public static function getPostInfo($postId, $db){
    $sql = "SELECT 
                p.*,
                u." . UserField::FIRST_NAME . " as author_first_name,
                u." . UserField::LAST_NAME . " as author_last_name,
				u." . UserField::AVATAR . " as author_avatar
            FROM posts p
            JOIN users u ON p." . PostField::AUTHOR_ID . " = u." . UserField::ID . "
            WHERE p." . PostField::POST_ID . " = ?";
    
    $resArr = $db->fetchOne($sql, [$postId]);
    return $resArr;
}
public function createPost($autor_id, $wall_owner_id, $message, $image_path)
{
    $data = [
        PostField::AUTHOR_ID => $autor_id,
        PostField::OWNER_ID => $wall_owner_id,
        PostField::MSG => $message,
        PostField::IMG => $image_path,
        PostField::DATE => date('Y-m-d H:i:s')
    ];
    
    return $this->db->insert('posts', $data);
}

public function commentPost($post_id, $comment, $author_id){
	  $data = [
		PostField::POST_ID =>$post_id,
        'comment' => $comment,
		'date' => date('Y-m-d H:i:s'),
		'author_id' => $author_id
    ];
	return $this->db->insert('post_comments', $data);
}

public function updatePost($post_id, $message, $image_path){
	$new_data = [
        PostField::MSG => $message,
        PostField::IMG => $image_path,
    ];
	return $this->db->update('posts', $new_data, 'post_id = ?', [$post_id]);
}

}
?>