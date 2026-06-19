<?php
header('Content-Type: application/json');
require_once '../classes/Post.php';
require_once '../includes/init.php';
$postModel = new Post($db);
if(isset($_SESSION['id'])){
	$current_user_id = $_SESSION['id'];
}
else $current_user_id=null;



if (isset($_GET['user_id'])) {
	$wall_owner_id = $_GET['user_id'] ?? null;
    $posts = $postModel->getUserPosts($wall_owner_id); //стена
} else {
    $posts = $postModel->getNewestPosts(); //общая лента
}

foreach ($posts as &$post) {
	$is_author = ($current_user_id && $post['autor_id'] == $current_user_id);
	$is_wall_owner = ($current_user_id && $post['wall_owner_id'] == $current_user_id);
	$is_admin = User::getUserById($current_user_id, $db)?->isAdmin();

    $post['can_edit'] = ($current_user_id && $is_author);
    $post['can_delete'] = ($current_user_id && ($is_author|| $is_wall_owner || $is_admin ));
}

echo json_encode($posts);
?>