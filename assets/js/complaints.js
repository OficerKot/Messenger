// ===== ЖАЛОБА НА ПОЛЬЗОВАТЕЛЯ =====
document.addEventListener('DOMContentLoaded', function() {
    const complaintBtn = document.querySelector('.complaint-btn');
    const modal = document.getElementById('complaintModal');
    const overlay = document.getElementById('complaintModalOverlay');
    const closeBtn = document.getElementById('closeComplaintModal');
    const cancelBtn = document.getElementById('cancelComplaintBtn');
    const sendBtn = document.getElementById('sendComplaintBtn');
    const messageField = document.getElementById('complaintMessage');
    
    if (!complaintBtn || !modal) return;
    
    // Открытие модального окна
    complaintBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'flex';
        // Сбрасываем форму
        document.querySelector('input[name="complaint_type"]:checked')?.click();
        messageField.value = '';
    });
    
    // Закрытие
    function closeModal() {
        modal.style.display = 'none';
    }
    
    if (overlay) overlay.addEventListener('click', closeModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    
    // Отправка жалобы
    sendBtn.addEventListener('click', function() {
        const userId = complaintBtn.dataset.userId;
        const selectedType = document.querySelector('input[name="complaint_type"]:checked');
        const message = messageField.value.trim();
        
        if (!selectedType) {
            alert('Выберите причину жалобы');
            return;
        }
        
        const typeId = selectedType.value;
        
        sendBtn.textContent = 'Отправка...';
        sendBtn.disabled = true;
        
        fetch('../handlers/send_complaint.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=' + userId + '&type=' + typeId + '&message=' + encodeURIComponent(message)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                complaintBtn.textContent = '✓ Жалоба отправлена';
                complaintBtn.classList.add('reported');
                complaintBtn.disabled = true;
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке жалобы');
        })
        .finally(() => {
            sendBtn.textContent = 'Отправить жалобу';
            sendBtn.disabled = false;
        });
    });
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
});