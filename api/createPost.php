<?php
header('Content-Type: application/json');

require_once '../classes/Post.php';
require_once '../includes/init.php';


if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$message = $_POST['message'] ?? '';
$wall_owner_id = $_POST['wall_owner_id'] ?? $_SESSION['id'];
$author_id = $_SESSION['id']; 


$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filename = uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $filename);
    $image_path = $filename;
}


$post = new Post($db);
$result = $post->createPost($author_id, $wall_owner_id, $message, $image_path);

if ($result) {
	$user = User::getUserById($author_id, $db);

    echo json_encode([
        'success' => true,
        'post' => [
			'post_id' => $result,
            'message' => $message,
            'image_path' => $image_path,
            'author_first_name' => $user->get(UserField::FIRST_NAME),
            'author_last_name' => $user->get(UserField::LAST_NAME),
			'author_avatar' => $user->get(UserField::AVATAR),
			'autor_id' => $author_id,     
            'wall_owner_id' => $wall_owner_id,
            'can_edit' => true,           
            'can_delete' => true, 
            'date' => date('Y-m-d H:i:s')
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Не удалось создать пост']);
}
?>