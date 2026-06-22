// ===== АДМИН: УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.admin-delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.closest('.userInfoContainer')?.querySelector('.userName')?.textContent?.trim().replace(/\s+/g, ' ') || 'пользователя';const button = this;
            
            // Чистый текст без лишних символов
            if (!confirm('Удалить пользователя "' + userName + '"?')) {
                return;
            }
            
            if (!confirm('Удалить пользователя и все его данные (посты, комментарии, друзей)?')) {
                return;
            }
            
            button.textContent = 'Удаление...';
            button.disabled = true;
            
            fetch('../handlers/admin_delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=' + userId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/friends/friends.php';
                } else {
                    alert('Ошибка: ' + data.message);
                    button.textContent = '🗑 Удалить пользователя';
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при удалении пользователя');
                button.textContent = '🗑 Удалить пользователя';
                button.disabled = false;
            });
        });
    });
});