<?php
session_start();
header('Content-Type: application/json');
include '../includes/init.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['id'];
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
$limit = (int)$limit;
$sql = "SELECT n.*, 
        CONCAT(u.first_name, ' ', u.last_name) as sender_name,
        u.login as sender_login,
        u.avatar as sender_avatar
        FROM notifications n
        JOIN users u ON n.sender_id = u.user_id
        WHERE n.receiver_id = ?
        ORDER BY n.created_at DESC
        LIMIT $limit";

$notifications = $db->fetchAll($sql, [$user_id]);

$html = '';

if (empty($notifications)) {
    $html = '
        <div class="no-notifications">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#818c99" stroke-width="1.5">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <p>Нет уведомлений</p>
        </div>
    ';
} else {
    foreach ($notifications as $notif) {
        $avatar = $notif['sender_avatar'] ?? 'baseimage.jpg';
        $sender_name = htmlspecialchars($notif['sender_name']);
        $message = '';
        
        switch($notif['type']) {
            case 'friend_request':
                $message = 'отправил(а) вам заявку в друзья';
                break;
            case 'friend_accept':
                $message = 'принял(а) вашу заявку в друзья';
                break;
            default:
                $message = htmlspecialchars($notif['message']);
        }
        
        $is_read = $notif['is_read'] ? 'read' : 'unread';
        
        $html .= '
            <a href="../profile/userWall.php?user_id=' . $notif['sender_id'] . '" 
               style="text-decoration: none; color: inherit; display: block;">
                <div class="notification-item ' . $is_read . '" 
                     data-notification-id="' . $notif['notification_id'] . '">
                    <div class="notification-avatar">
                        <img src="../assets/uploads/' . $avatar . '" 
                             alt="Avatar" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">
                            <strong>' . $sender_name . '</strong> ' . $message . '
                        </div>
                        <div class="notification-time">' . timeAgo($notif['created_at']) . '</div>
                    </div>
                </div>
            </a>
        ';
    }
}

function timeAgo($timestamp) {
    if (!$timestamp) return '';
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) return 'только что';
    if ($diff < 3600) return floor($diff / 60) . ' мин назад';
    if ($diff < 86400) return floor($diff / 3600) . ' ч назад';
    if ($diff < 604800) return floor($diff / 86400) . ' дн назад';
    return date('d.m.Y', $time);
}

echo json_encode([
    'success' => true,
    'html' => $html,
    'notifications' => $notifications
]);
?>