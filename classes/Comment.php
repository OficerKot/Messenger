<?php 
class CommentField {
    const COMMENT_ID = 'comment_id';
    const POST_ID = 'post_id';
    const AUTHOR_ID = 'author_id';
    const COMMENT = 'comment';
    const DATE = 'date';
}

class Comment {
    private $db;  
    
    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getPostComments($post_id) {
        $sql = "SELECT 
                    c.*,
                    u." . UserField::FIRST_NAME . " as author_first_name,
                    u." . UserField::LAST_NAME . " as author_last_name,
                    u." . UserField::AVATAR . " as author_avatar
                FROM post_comments c
                JOIN users u ON c." . CommentField::AUTHOR_ID . " = u." . UserField::ID . "
                WHERE c." . CommentField::POST_ID . " = ?
                ORDER BY c." . CommentField::DATE . " ASC";
        
        return $this->db->fetchAll($sql, [$post_id]);
    }
    public function getCommentInfo($comment_id) {
        $sql = "SELECT 
                    c.*,
                    u." . UserField::FIRST_NAME . " as author_first_name,
                    u." . UserField::LAST_NAME . " as author_last_name,
                    u." . UserField::AVATAR . " as author_avatar
                FROM post_comments c
                JOIN users u ON c." . CommentField::AUTHOR_ID . " = u." . UserField::ID . "
                WHERE c." . CommentField::COMMENT_ID . " = ?";
        
        return $this->db->fetchOne($sql, [$comment_id]);
    }

    public function createComment($post_id, $comment, $author_id) {
        $data = [
            CommentField::POST_ID => $post_id,
            CommentField::COMMENT => $comment,
            CommentField::DATE => date('Y-m-d H:i:s'),
            CommentField::AUTHOR_ID => $author_id
        ];
        return $this->db->insert('post_comments', $data);
    }

    public function updateComment($comment_id, $comment) {
        $new_data = [
            CommentField::COMMENT => $comment,
        ];
        return $this->db->update('post_comments', $new_data, 'comment_id = ?', [$comment_id]);
    }

    public function deleteComment($comment_id) {
        return $this->db->delete('post_comments', 'comment_id = ?', [$comment_id]);
    }
}
?>