// ===== АДМИН: УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.admin-delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.closest('.userInfoContainer')?.querySelector('.userName')?.textContent?.trim() || 'пользователя';
            const button = this;
            
            if (!confirm(`Вы уверены, что хотите удалить пользователя "${userName}"?\n\nЭто действие НЕОБРАТИМО!`)) {
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
                console.log('Полный путь к друзьям:', window.location.origin + '/friends/friends.php');
                if (data.success) {
                    window.location.href = '/friends/friends.php';
                }
            })
        });
    });
});