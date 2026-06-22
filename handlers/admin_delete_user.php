<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы']);
    exit();
}

$admin_id = $_SESSION['id'];
$target_user_id = intval($_POST['user_id']);

// Проверяем, что текущий пользователь - админ
$admin_check = "SELECT role FROM users WHERE user_id = ?";
$admin_result = $db->fetchOne($admin_check, [$admin_id]);

if (!$admin_result || $admin_result['role'] != '1') {
    echo json_encode(['success' => false, 'message' => 'Недостаточно прав']);
    exit();
}

// Нельзя удалить админа
$target_check = "SELECT role FROM users WHERE user_id = ?";
$target_result = $db->fetchOne($target_check, [$target_user_id]);

if ($target_result && $target_result['role'] == '1') {
    echo json_encode(['success' => false, 'message' => 'Нельзя удалить другого администратора']);
    exit();
}

// Нельзя удалить себя
if ($admin_id == $target_user_id) {
    echo json_encode(['success' => false, 'message' => 'Нельзя удалить самого себя']);
    exit();
}

// ===== УДАЛЯЕМ ВСЕ СВЯЗАННЫЕ ДАННЫЕ =====

// // 1. Удаляем жалобы (сначала, чтобы не было ошибок внешних ключей)
// $db->delete('complains', 'sender_id = ? OR target_user_id = ?', [$target_user_id, $target_user_id]);

// // 2. Удаляем уведомления
// $db->delete('notifications', 'receiver_id = ? OR sender_id = ?', [$target_user_id, $target_user_id]);

// // 3. Удаляем записи о друзьях
// $db->delete('friends', 'user_id = ? OR friend_id = ?', [$target_user_id, $target_user_id]);

// // 4. Удаляем комментарии к постам
// $db->delete('post_comments', 'user_id = ?', [$target_user_id]);

// // 5. Удаляем изображения постов (получаем пути)
// $images_sql = "SELECT image_path FROM post_images WHERE post_id IN (SELECT post_id FROM posts WHERE autor_id = ?)";
// $images = $db->fetchAll($images_sql, [$target_user_id]);
// foreach ($images as $img) {
//     if ($img['image_path'] && file_exists('../assets/uploads/' . $img['image_path'])) {
//         unlink('../assets/uploads/' . $img['image_path']);
//     }
// }

// // 6. Удаляем изображения постов
// $db->delete('post_images', 'post_id IN (SELECT post_id FROM posts WHERE autor_id = ?)', [$target_user_id]);

// // 7. Удаляем посты
// $db->delete('posts', 'autor_id = ? OR wall_owner_id = ?', [$target_user_id, $target_user_id]);

// // 8. Удаляем аватарку
// $avatar = $target_result['avatar'] ?? 'baseimage.jpg';
// if ($avatar && $avatar != 'baseimage.jpg' && file_exists('../assets/uploads/' . $avatar)) {
//     unlink('../assets/uploads/' . $avatar);
// }

// // 9. Удаляем самого пользователя
$db->delete('users', 'user_id = ?', [$target_user_id]);

echo json_encode(['success' => true, 'message' => 'Пользователь удалён']);
?>