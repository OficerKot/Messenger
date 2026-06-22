<?php session_start();
include '../includes/init.php';

$other_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$other_user = $other_user_id? User::getUserById($other_user_id, $db): null;

$this_user = isset($_SESSION['id']) ? $_SESSION['id'] : null;
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if ($other_user){ ?>
	<title><?php echo $other_user->get(UserField::FIRST_NAME);?> <?php echo $other_user->get(UserField::LAST_NAME); ?>
	</title>
	<?php } 
	else { ?><title>Сообщения</title> <?php } ?>

	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/messages.css">

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
				<!-- Заполняется через js -->
			</div>
			<div class="msg-container">
				<div class="msg-list" id="messagesContainer"></div>

				<!--Форма для отправки сообщения -->

				<?php if(AccessHelper::HasAccessToWall($this_user, $other_user_id, $db)){ ?>
				<form id="msg-form" class="msg-form">
					<div style="display: flex; flex-direction: row;">
						<textarea id="msgText" placeholder="Введите сообщение..."></textarea>
						<button type="submit">Отправить</button>
					</div>
					<input id="msgImage" type="file">

				</form>
				<?php } ?>
			</div>
		</div>

		<!-- Правая колонка -->
		<?php include "../includes/menuRight.php";?>
	</div>
	<script src="../assets/js/messages/messageApi.js"></script>
	<script src="../assets/js/messages/messageView.js"></script>
	<script src="../assets/js/messages/messages.js"></script>


</body>

</html>