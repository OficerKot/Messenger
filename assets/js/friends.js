// ===== УПРАВЛЕНИЕ ДРУЗЬЯМИ =====

// Отправка заявки в друзья
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.send-request').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const button = this;
            const originalText = button.textContent;
            
            button.textContent = 'Отправка...';
            button.disabled = true;
            
            fetch('../friends/requests.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=' + userId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = '✓ Заявка отправлена';
                    button.classList.remove('send-request');
                    button.classList.add('pending');
                    button.style.backgroundColor = '#e7e8ec';
                    button.style.color = '#818c99';
                    button.disabled = true;
                } else {
                    alert('Ошибка: ' + data.message);
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка');
                button.textContent = originalText;
                button.disabled = false;
            });
        });
    });

    // Принятие заявки в друзья
    document.querySelectorAll('.accept-request').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const button = this;
            const originalText = button.textContent;
            
            button.textContent = 'Принятие...';
            button.disabled = true;
            
            fetch('../friends/accept_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=' + userId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = '✓ Вы друзья';
                    button.classList.remove('accept-request');
                    button.classList.add('friends');
                    button.style.backgroundColor = '#e8f5e9';
                    button.style.color = '#4caf50';
                    button.disabled = true;
                    
                    // Перезагружаем страницу через секунду
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Ошибка: ' + data.message);
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка');
                button.textContent = originalText;
                button.disabled = false;
            });
        });
    });
});