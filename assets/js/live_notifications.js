document.addEventListener('DOMContentLoaded', () => {

    const wrapper = document.querySelector('.notifications-wrapper');

    if (!wrapper) {
        return;
    }

    let lastId = parseInt(wrapper.dataset.lastId) || 0;

    const btn = document.getElementById('notificationsBtn');
    const dropdown = document.getElementById('notificationsDropdown');
    const markAllBtn = document.getElementById('markAllRead');
    const list = document.getElementById('notificationsList');

    // =========================
    // БЕЙДЖ
    // =========================

    function updateBadge(count) {

        let badge = btn.querySelector('.notifications-badge');

        if (count > 0) {

            if (!badge) {

                badge = document.createElement('span');
                badge.className = 'notifications-badge';

                btn.appendChild(badge);
            }

            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'inline';

        } else if (badge) {

            badge.style.display = 'none';
        }
    }

    // =========================
    // ОБНОВИТЬ СПИСОК
    // =========================

    async function refreshNotificationList() {

        try {

            const response = await fetch(
                '../handlers/get_recent_notifications.php',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },
                    body: 'limit=5'
                }
            );

            const data = await response.json();

            if (data.success) {
                list.innerHTML = data.html;
            }

            return data;

        } catch (error) {

            console.error(
                'Error refreshing notifications:',
                error
            );

            return null;
        }
    }
    console.log(
        [...list.querySelectorAll('.notification-item')]
            .map(x => ({
                id: x.dataset.notificationId,
                class: x.className
            }))
    );

    // =========================
    // ПРОЧИТАТЬ
    // =========================

    async function markAsRead(ids = null) {

        try {

            let body;

            if (ids === null) {

                body = 'all=1';

            } else if (ids.length > 0) {

                body =
                    'ids=' +
                    encodeURIComponent(
                        JSON.stringify(ids)
                    );

            } else {

                return;
            }

            const response = await fetch(
                '../handlers/mark_notifications_read.php',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },
                    body
                }
            );

            const data = await response.json();

            if (data.success) {

                updateBadge(0);

                document
                    .querySelectorAll('.notification-item.unread')
                    .forEach(item => {

                        item.classList.remove('unread');
                        item.classList.add('read');
                    });

                if (data.last_id) {
                    lastId = data.last_id;
                }
            }

            return data;

        } catch (error) {

            console.error(
                'Error marking notifications:',
                error
            );
        }
    }

    // =========================
    // POLLING
    // =========================

    async function checkAndUpdateBadge() {

        try {

            const response = await fetch(
                '../handlers/check_notifications.php',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },
                    body:
                        'last_id=' +
                        encodeURIComponent(lastId)
                }
            );

            const data = await response.json();

            if (!data.success) {
                return;
            }

            updateBadge(data.unread_count);

            if (data.last_id > lastId) {

                lastId = data.last_id;

                if (
                    dropdown &&
                    dropdown.classList.contains('show')
                ) {
                    refreshNotificationList();
                }
            }

        } catch (error) {

            console.error(
                'Polling error:',
                error
            );
        }
    }

    // =========================
    // ОТКРЫТИЕ КОЛОКОЛА
    // =========================

    if (btn) {

        btn.addEventListener(
            'click',
            async function (e) {

                e.stopPropagation();

                dropdown.classList.toggle('show');

                if (
                    !dropdown.classList.contains('show')
                ) {
                    return;
                }

                await refreshNotificationList();

                const unreadItems =
                    list.querySelectorAll(
                        '.notification-item.unread'
                    );

                const ids =
                    [...unreadItems].map(
                        item =>
                            item.dataset.notificationId
                    );

                if (ids.length > 0) {

                    await markAsRead(ids);

                } else {

                    checkAndUpdateBadge();
                }
            }
        );
    }

    // =========================
    // ЗАКРЫТЬ ПО КЛИКУ ВНЕ
    // =========================

    document.addEventListener('click', e => {

        if (
            btn &&
            !btn.contains(e.target) &&
            dropdown &&
            !dropdown.contains(e.target)
        ) {
            dropdown.classList.remove('show');
        }
    });

    // =========================
    // ОТМЕТИТЬ ВСЕ
    // =========================

    if (markAllBtn) {

        markAllBtn.addEventListener(
            'click',
            async () => {

                await markAsRead();

                await refreshNotificationList();

                checkAndUpdateBadge();
            }
        );
    }

    // =========================
    // СТАРТ
    // =========================

    checkAndUpdateBadge();

    setInterval(
        checkAndUpdateBadge,
        5000
    );
});