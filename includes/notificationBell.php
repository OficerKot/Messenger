<?php
if (!isset($_SESSION['id'])) {
    return;
}

include_once __DIR__ . '/../includes/init.php';

$notificationManager = new NotificationManager($db, $_SESSION['id']);
$unread_count = $notificationManager->getUnreadCount();
$notifications = $notificationManager->getRecent(5);

// Получаем ID последнего уведомления
$last_id = 0;
if (!empty($notifications)) {
    $last_id = $notifications[0]['notification_id'];
}
?>
<link rel="stylesheet" href="../assets/css/style.css">
<div class="notifications-wrapper" data-last-id="<?php echo $last_id; ?>">
	<button class="notifications-btn" id="notificationsBtn">
		<svg class="bell-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
			stroke-width="2">
			<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
			<path d="M13.73 21a2 2 0 0 1-3.46 0" />
		</svg>
		<?php if ($unread_count > 0): ?>
		<span class="notifications-badge"><?php echo $unread_count > 99 ? '99+' : $unread_count; ?></span>
		<?php endif; ?>
	</button>

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
			<?php foreach ($notifications as $notif): 
                    // Получаем аватарку отправителя
                    $sender_avatar = 'baseimage.jpg';
                    $avatar_sql = "SELECT avatar FROM users WHERE user_id = ?";
                    $avatar_result = $db->fetchOne($avatar_sql, [$notif['sender_id']]);
                    if ($avatar_result && !empty($avatar_result['avatar'])) {
                        $sender_avatar = $avatar_result['avatar'];
                    }
                ?>
			<a href="../profile/userWall.php?user_id=<?php echo $notif['sender_id']; ?>"
				style="text-decoration: none; color: inherit; display: block;">
				<div class="notification-item <?php echo $notif['is_read'] ? 'read' : 'unread'; ?>"
					data-notification-id="<?php echo $notif['notification_id']; ?>">
					<div class="notification-avatar">
						<img src="../assets/uploads/<?php echo $sender_avatar; ?>" alt="Avatar"
							style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
					</div>
					<div class="notification-content">
						<div class="notification-text">
							<strong><?php echo htmlspecialchars($notif['sender_name']); ?></strong>

						</div>
						<div class="notification-time">
							<?php echo timeAgo($notif['created_at']); ?>
						</div>
					</div>
				</div>
			</a>
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