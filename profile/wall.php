<?php session_start();
$login = $_SESSION['login'];
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
	<div class="header-fullwidth">
		<h1>Название</h1>

		<!-- ПОИСКОВАЯ СТРОКА -->
		<div class="search-container">
			<div class="search-box">
				<svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="10.5" cy="10.5" r="6.5" />
					<line x1="21" y1="21" x2="15" y2="15" />
				</svg>
				<input type="text" class="search-input" placeholder="Поиск">
			</div>
		</div>

		<!-- ПРАВАЯ ЧАСТЬ ХЕДЕРА -->
		<div class="header-right">
			<?php if (isset($_SESSION['login'])):?>
			<a href="logout.php" style="text-decoration: none;">Выход</a>
			<div class="avatar">А</div>
			<?php else:?>
			<div>Вы вошли как гость</div>
			<?php endif;?>
		</div>
	</div>

	<!-- КОНТЕЙНЕР С ТРЕМЯ КОЛОНКАМИ -->
	<div class="columns-container">
		<!-- Левая колонка (меню) -->
		<?php include "menuLeft.php";?>

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
						<div class="post-avatar">М</div>
						<div class="post-author">Мария Савченко</div>
						<div class="post-time">Вчера</div>
					</div>
					<div class="post-content">
						Фотография
					</div>
					<div class="post-actions">
						<div class="post-action">❤️ 128</div>
						<div class="post-action">💬 34</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Правая колонка -->
		<?php include "menuRight.php";?>
	</div>

	<script src="../assets/js/script.js" defer></script>
</body>

</html>