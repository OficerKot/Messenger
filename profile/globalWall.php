<?php session_start();
include '../includes/connectDB.php';
include '../classes/User.php';
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Мой сайт</title>
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="icon" type="image/x-icon" href="Olesya_iconka.ico">
</head>

<body>
	<div class="menu-btn" id="menuBtn">&#9776;</div>

	<!-- ВЕРХНИЙ БЛОК С ПОИСКОМ -->
	<?php include "../includes/header.php";?>

	<!-- КОНТЕЙНЕР С ТРЕМЯ КОЛОНКАМИ -->
	<div class="columns-container">
		<!-- Левая колонка (меню) -->
		<?php include "../includes/menuLeft.php";?>

		<!-- Центральная колонка (контент) -->
		<div class="center-panel">
			<div class="top-block">
				<h2>Основной контент</h2>
			</div>
			<div class="center-panel1">
				<!-- ПОСТЫ -->
				<div class="post">
					<div class="post-header">
						<div class="post-avatar">С</div>
						<div class="post-author">Софья Милютина</div>
						<div class="post-time">2 ч назад</div>
					</div>
					<div class="post-content">
						Привет!
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 24</div>
						<div class="post-action">💬 7</div>
					</div>
				</div>

				<div class="post">
					<div class="post-header">
						<div class="post-avatar">А</div>
						<div class="post-author">Алексей Иванов</div>
						<div class="post-time">5 ч назад</div>
					</div>
					<div class="post-content">
						Ухты!
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 56</div>
						<div class="post-action">💬 12</div>
					</div>
				</div>

				<div class="post">
					<div class="post-header">
						<div class="post-avatar">С</div>
						<div class="post-author">Софья Милютина</div>
						<div class="post-time">2 ч назад</div>
					</div>
					<div class="post-content">
						Отличный день
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 24</div>
						<div class="post-action">💬 7</div>
					</div>
				</div>

				<div class="post">
					<div class="post-header">
						<div class="post-avatar">А</div>
						<div class="post-author">Алексей Иванов</div>
						<div class="post-time">5 ч назад</div>
					</div>
					<div class="post-content">
						Ухты!
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 56</div>
						<div class="post-action">💬 12</div>
					</div>
				</div>

				<div class="post">
					<div class="post-header">
						<div class="post-avatar">А</div>
						<div class="post-author">Алина Гдалёва</div>
						<div class="post-time">Вчера</div>
					</div>
					<div class="post-content">
						Хочу спать
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 1933</div>
						<div class="post-action">💬 340</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Правая колонка -->
		<?php include "../includes/menuRight.php";?>
	</div>

	<script src="../assets/js/script.js" defer></script>
</body>

</html>