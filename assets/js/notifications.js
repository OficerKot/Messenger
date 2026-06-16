// Уведомления
document.addEventListener('DOMContentLoaded', function() {
    const notificationsBtn = document.getElementById('notificationsBtn');
    const dropdown = document.getElementById('notificationsDropdown');
    
    if (!notificationsBtn || !dropdown) return;
    
    // Открытие/закрытие окна уведомлений
    notificationsBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
        
        // Если открыли окно, отмечаем уведомления как прочитанные
        if (dropdown.classList.contains('show')) {
            markCurrentNotificationsAsRead();
        }
    });
    
    // Закрытие при клике вне окна
    document.addEventListener('click', function(e) {
        if (!notificationsBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Отметка всех уведомлений как прочитанных
    const markAllReadBtn = document.getElementById('markAllRead');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            fetch('../handlers/mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'all=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = notificationsBtn.querySelector('.notifications-badge');
                    if (badge) badge.remove();
                    
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                    });
                }
            });
        });
    }
    
    function markCurrentNotificationsAsRead() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        if (unreadItems.length === 0) return;
        
        const notificationIds = Array.from(unreadItems).map(item => 
            item.dataset.notificationId
        );
        
        fetch('../handlers/mark_notifications_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'ids=' + JSON.stringify(notificationIds)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                });
                
                const badge = notificationsBtn.querySelector('.notifications-badge');
                if (badge) badge.remove();
            }
        });
    }
});