// ===== ПОИСК В ХЕДЕРЕ =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('headerSearchInput');
    const dropdown = document.getElementById('searchResultsDropdown');
    const resultsList = document.getElementById('searchResultsList');
    let searchTimeout = null;

    if (!searchInput || !dropdown || !resultsList) return;

    function searchUsers(query) {
        if (query.length < 2) {
            resultsList.innerHTML = '<div class="search-empty">Введите минимум 2 символа</div>';
            dropdown.classList.remove('show');
            return;
        }

        // Используем ТОТ ЖЕ САМЫЙ обработчик, что и в friends.php
        fetch('../handlers/search_users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'query=' + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                resultsList.innerHTML = '<div class="search-empty">Ошибка поиска</div>';
                return;
            }

            if (!data.html || data.html.includes('Пользователи не найдены')) {
                resultsList.innerHTML = '<div class="search-empty">Пользователи не найдены</div>';
                dropdown.classList.add('show');
                return;
            }

            // Используем HTML, который вернул сервер
            // Но нам нужен только список пользователей, без обёртки
            // Поэтому парсим и извлекаем только ссылки
            
            // Вариант 1: Просто вставляем HTML (если он подходит по стилю)
            resultsList.innerHTML = data.html;
            dropdown.classList.add('show');
        })
        .catch(error => {
            console.error('Search error:', error);
            resultsList.innerHTML = '<div class="search-empty">Ошибка поиска</div>';
        });
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchUsers(query);
        }, 300);
    });

    // Закрытие при клике вне
    document.addEventListener('click', function(e) {
        const container = document.querySelector('.search-container');
        if (container && !container.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Закрытие по ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdown.classList.remove('show');
            searchInput.blur();
        }
    });
});