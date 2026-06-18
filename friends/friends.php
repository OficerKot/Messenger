<?php 
session_start();
include '../includes/init.php';

if(!isset($_SESSION['id'])){
    header('Location:../index.php');
    exit;
}
$id = $_SESSION['id'];

$current_user = User::getUserById($id, $db);
$all_users = User::getAllUsersExcept($id, $db); 
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Друзья - Моя соцсеть</title>
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="icon" type="image/x-icon" href="Olesya_iconka.ico">
</head>

<body>
	<div class="menu-btn" id="menuBtn">&#9776;</div>

	<?php include "../includes/header.php";?>

	<div class="columns-container">
		<?php include "../includes/menuLeft.php";?>

		<div class="center-panel">
			<div class="top-block">
				<h2>Все пользователи</h2>
			</div>

			<div class="center-panel1">
				<div class="users-list">
					<?php if (count($all_users) > 0): ?>
					<?php foreach ($all_users as $user_data): 
                            // Создаём объект User для каждого пользователя в списке
                            $friend = User::getUserById($user_data[UserField::ID], $db);
                            $friendship_status = $current_user->getFriendshipStatus($user_data['user_id']);
                        ?>
					<div class="user-card">
						<div class="user-info">
							<div class="user-name">
								<?php echo htmlspecialchars($friend->get(UserField::FIRST_NAME)); ?>
								<?php echo htmlspecialchars($friend->get(UserField::LAST_NAME)); ?>
							</div>
							<div class="user-login">
								@<?php echo htmlspecialchars($friend->get(UserField::LOGIN)); ?>
							</div>

							<?php if ($friendship_status == 'none'): ?>
							<button class="requestBtn" data-user-id="<?php echo $user_data['user_id']; ?>">
								Отправить заявку
							</button>
							<?php elseif ($friendship_status == 'pending'): ?>
							<button class="requestBtn pending" disabled>
								Заявка отправлена
							</button>
							<?php elseif ($friendship_status == 'accepted'): ?>
							<button class="requestBtn friends" disabled>
								Вы друзья ✓
							</button>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
					<?php else: ?>
					<div class="empty-message">
						<p>Пользователей пока нет</p>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php include "../includes/menuRight.php";?>
	</div>

	<script>
	document.querySelectorAll('.requestBtn').forEach(button => {
		button.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			const userId = this.dataset.userId;
			const button = this;

			const originalText = button.textContent;
			button.textContent = 'Отправка...';
			button.disabled = true;

			fetch('requests.php', {
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
					alert('Произошла ошибка при отправке заявки');
					button.textContent = originalText;
					button.disabled = false;
				});
		});
	});
	</script>
</body>

</html>