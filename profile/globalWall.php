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
	<link rel="stylesheet" href="../assets/css/user_wall.css">
</head>

<body>


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
			<div class="center-panel1" id="postsContainer">


			</div>
		</div>

		<!-- Правая колонка -->
		<?php include "../includes/menuRight.php";?>
	</div>


	<script src="../assets/js/script.js" defer></script>
	<script src="../assets/js/postView.js"></script>
	<script src="../assets/js/postApi.js"></script>

	<script src="../assets/js/comments/CommentView.js"></script>
	<script src="../assets/js/comments/commentApi.js"></script>

	<script src="../assets/js/postsLoader.js"></script>

</body>

</html>