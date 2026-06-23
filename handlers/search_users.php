<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit();
}

$query = isset($_POST['query']) ? trim($_POST['query']) : '';
$current_user_id = $_SESSION['id'];

if (empty($query)) {
    $users = User::getAllUsersExcept($current_user_id, $db);
} else {
    $users = User::searchUsers($query, $current_user_id, $db);
}

$html = '';

if (empty($users)) {
    $html = '<div class="search-empty">Пользователи не найдены</div>';
} else {
    foreach ($users as $user) {
        $avatar = $user['avatar'] ?? 'baseimage.jpg';
        $html .= '
            <a href="../profile/userWall.php?user_id=' . $user['user_id'] . '" 
               class="search-result-item">
                <div class="search-result-avatar">
                    <img src="../assets/uploads/' . $avatar . '" 
                         alt="Avatar" 
                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                </div>
                <div class="search-result-info">
                    <div class="search-result-name">
                        ' . htmlspecialchars($user['first_name']) . ' 
                        ' . htmlspecialchars($user['last_name']) . '
                    </div>
                    <div class="search-result-login">
                        @' . htmlspecialchars($user['login']) . '
                    </div>
                </div>
            </a>
        ';
    }
}

echo json_encode([
    'success' => true,
    'html' => $html,
    'count' => count($users)
]);
?>