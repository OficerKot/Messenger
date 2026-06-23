// ===== ПОИСК ПОЛЬЗОВАТЕЛЕЙ =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUsersInput');
    const usersList = document.getElementById('usersList');
    let searchTimeout = null;

    if (!searchInput || !usersList) return;

    function loadUsers(query = '') {
        fetch('../handlers/search_users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'query=' + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                usersList.innerHTML = data.html;
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        });
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(function() {
            loadUsers(query);
        }, 300);
    });

    // ===== ПРИ ЗАГРУЗКЕ СТРАНИЦЫ =====
    // Всегда очищаем поле поиска и показываем всех пользователей
    searchInput.value = '';
    // loadUsers(''); // НЕ НУЖНО, т.к. PHP уже показал всех
});