<?php
if (!isset($_SESSION['id'])) {
    return;
}

include_once '../classes/NotificationManager.php';
echo "<!-- receiver_id: " . $_SESSION['id'] . " -->";
$notificationManager = new NotificationManager($conn, $_SESSION['id']);
$unread_count = $notificationManager->getUnreadCount();
echo "<!-- unread_count: $unread_count -->"; // Должно быть > 0

$notifications = $notificationManager->getRecent(5);
?>

<div class="notifications-wrapper">
    <button class="notifications-btn" id="notificationsBtn">
        <svg class="bell-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <?php if ($unread_count > 0): ?>
            <span class="notifications-badge"><?php echo $unread_count > 99 ? '99+' : $unread_count; ?></span>
        <?php endif; ?>
    </button>
    
    <!-- Выпадающее окно уведомлений -->
    <div class="notifications-dropdown" id="notificationsDropdown">
        <div class="dropdown-header">
            <span class="dropdown-title">Уведомления</span>
            <?php if ($unread_count > 0): ?>
                <button class="mark-all-read" id="markAllRead">Отметить все</button>
            <?php endif; ?>
        </div>
        
        <div class="dropdown-content" id="notificationsList">
            <?php if (empty($notifications)): ?>
                <div class="no-notifications">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#818c99" stroke-width="1.5">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <p>Нет уведомлений</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item <?php echo $notif['is_read'] ? 'read' : 'unread'; ?>" 
                         data-notification-id="<?php echo $notif['notification_id']; ?>">
                        <div class="notification-avatar">
                            <?php echo strtoupper(substr($notif['sender_name'], 0, 1)); ?>
                        </div>
                        <div class="notification-content">
                            <div class="notification-text">
                                <strong><?php echo htmlspecialchars($notif['sender_name']); ?></strong>
                                <?php 
                                switch($notif['type']) {
                                    case 'friend_request':
                                        echo "отправил(а) вам заявку в друзья";
                                        break;
                                    case 'friend_accept':
                                        echo "принял(а) вашу заявку в друзья";
                                        break;
                                    default:
                                        echo htmlspecialchars($notif['message']);
                                }
                                ?>
                            </div>
                            <div class="notification-time">
                                <?php echo timeAgo($notif['created_at']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
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
?>