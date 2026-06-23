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

				<!-- ===== ПОЛЕ ПОИСКА ===== -->
				<div class="search-users-container">
					<input type="text" id="searchUsersInput" placeholder="Поиск по имени, фамилии или логину..."
						class="search-users-input">
				</div>
			</div>

			<div class="center-panel1">
				<div class="users-list" id="usersList">
					<?php if (count($all_users) > 0): ?>
					<?php foreach ($all_users as $user_data): 
                            $avatar = $user_data['avatar'] ?? 'baseimage.jpg';
                        ?>
					<a href="../profile/userWall.php?user_id=<?php echo $user_data['user_id']; ?>"
						style="text-decoration: none; color: inherit; display: block;">
						<div class="user-card"
							style="display: flex; flex-direction: row; justify-content: space-between;">
							<div>
								<div class="user-avatar">
									<img src="../assets/uploads/<?php echo $avatar; ?>" alt="Avatar"
										style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
								</div>
								<div class="user-info">
									<div class="user-name">
										<?php echo htmlspecialchars($user_data['first_name']); ?>
										<?php echo htmlspecialchars($user_data['last_name']); ?>
									</div>
									<div class="user-login">
										@<?php echo htmlspecialchars($user_data['login']); ?>
									</div>
								</div>
							</div>

							<a class="friends-send-msg-btn"
								href="../messages/messages.php?user_id=<?php echo $user_data['user_id']; ?>">Отправить
								сообщение</button>
						</div>
					</a>
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

	<!-- ===== ПОДКЛЮЧАЕМ СКРИПТЫ ===== -->
	<script src="../assets/js/script.js" defer></script>
	<script src="../assets/js/search_users.js" defer></script>
</body>

</html>