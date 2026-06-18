<?php 
include "../includes/init.php";

// Получаем ID из GET-параметра
$wall_owner_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($wall_owner_id == 0) {
    header('Location: friends.php');
    exit;
}

$wall_owner = User::getUserById($wall_owner_id, $db);

if (!$wall_owner) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Пользователь не найден</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <?php include '../includes/header.php'; ?>
        <div class="columns-container">
            <?php include '../includes/menuLeft.php'; ?>
            <div class="center-panel">
                <div class="pageNotFound">Пользователь не найден</div>
            </div>
            <?php include '../includes/menuRight.php'; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$profile_img = $wall_owner->get(UserField::AVATAR);

// Проверяем статус дружбы
$friendship_status = 'none';
$is_owner = false;
$incoming_request = false; // Есть ли входящая заявка от этого пользователя

if (isset($_SESSION['id'])) {
    $current_user_id = $_SESSION['id'];
    $is_owner = ($current_user_id == $wall_owner_id);
    
    if (!$is_owner) {
		$current_user = User::getUserById($current_user_id, $db);
		if ($current_user) {
			$friendship_status = $current_user->getFriendshipStatus($wall_owner_id);
			
			// Проверяем, отправил ли текущий пользователь заявку
			$sent_request = $current_user->hasSentRequestTo($wall_owner_id);
			
			// Проверяем, есть ли входящая заявка
			$incoming_request = $current_user->hasIncomingRequestFrom($wall_owner_id);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/user_wall.css">
    <title><?php echo $wall_owner->get(UserField::FIRST_NAME); ?> <?php echo $wall_owner->get(UserField::LAST_NAME); ?></title>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="columns-container">

        <?php include '../includes/menuLeft.php'; ?>

        <div class="center-panel">
            <div class="center-panel1">
                <!-- Верхняя часть страницы-->
                <div class="userInfoContainer">
                    <div style="display: flex; flex-direction: column; flex: 1;">
                        <div class="userName">
                            <?php echo $wall_owner->get(UserField::FIRST_NAME); ?>
                            <?php echo $wall_owner->get(UserField::LAST_NAME); ?>
                        </div>

                        <div class="userLogin"> @<?php echo $wall_owner->get(UserField::LOGIN); ?></div>
                        <div class="otherInfo">
                            День рождения <?php echo $wall_owner->getFormattedBirthday(); ?>
                            (<?php echo $wall_owner->getAge(); ?> лет)
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <img src="../assets/uploads/<?php echo $profile_img; ?>" style="height: 200px; border-radius: 12px;">
                        
                        <!-- ===== КНОПКА ДЕЙСТВИЯ С ДРУЗЬЯМИ ===== -->
                        <?php if (isset($_SESSION['id']) && !$is_owner): ?>
    
    <!-- 1️⃣ УЖЕ ДРУЗЬЯ -->
    <?php if ($friendship_status == 'accepted'): ?>
        <button class="friend-btn friends" disabled>
            Вы друзья ✓
        </button>
    
    <!-- 2️⃣ МЫ ОТПРАВИЛИ ЗАЯВКУ (исходящая) -->
    <?php elseif ($sent_request): ?>
        <button class="friend-btn pending" disabled>
            Заявка отправлена
        </button>
    
    <!-- 3️⃣ НАМ ОТПРАВИЛИ ЗАЯВКУ (входящая) -->
    <?php elseif ($incoming_request): ?>
        <button class="friend-btn accept-request" data-user-id="<?php echo $wall_owner_id; ?>">
            Принять заявку в друзья
        </button>
    
    <!-- 4️⃣ НЕТ ЗАЯВОК -->
    <?php else: ?>
        <button class="friend-btn send-request" data-user-id="<?php echo $wall_owner_id; ?>">
            Добавить в друзья
        </button>
    <?php endif; ?>
    
<?php elseif ($is_owner): ?>
    <button class="friend-btn" style="background-color: #e7e8ec; color: #818c99; cursor: default;" disabled>
        Это ваш профиль
    </button>
<?php endif; ?>
                    </div>
                </div>

                <!-- Форма для создания поста -->
                <div>
                    <form id="postForm" class="postForm">
                        <textarea id="postMessage" placeholder="Что у вас нового?"></textarea>
                        <input id="postImage" type="file">
                        <button>Поделиться</button>
                    </form>
                </div>

                <!-- Посты -->
                <div style="width:100%; display: flex; justify-content: center; align-items: center;">
                    <h3>Все посты</h3>
                </div>
                <div class="postsContainer" id="postsContainer">
                    <script src="../assets/js/PostView.js"></script>
                    <script type="module" src="../assets/js/userWall.js"></script>
                </div>
            </div>
        </div>
        <?php include '../includes/menuRight.php'; ?>
    </div>

    <!-- AJAX для отправки и принятия заявок -->
    <script>
    // Отправка заявки
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

    // Принятие заявки
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
    </script>

</body>

</html>