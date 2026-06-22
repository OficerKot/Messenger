<?php 
session_start();
include '../includes/init.php';
if(!isset($_SESSION['id'])){ 
	header('Location:../index.php');
	exit;
};

include '../includes/connectDB.php';

$user = User::getUserById($_SESSION['id'], $db);
$editableFields = $user->getEditableFields();
?>


<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="../assets/css/profile.css">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/style.css">
	<title>Настройки</title>
</head>

<body>
	<?php include '../includes/header.php'; ?>
	<div class="columns-container">

		<?php include '../includes/menuLeft.php'; ?>
		<div class="center-panel">
			<h2>Настройки пользователя <?php echo $user->get(UserField::LOGIN); ?></h2>
			<form id="settingsForm" method="POST" enctype="multipart/form-data">
				<div>
					<label>Имя:</label>
					<input type="text" name="first_name" value="<?php echo $user->get(UserField::FIRST_NAME); ?>">
				</div>

				<div>
					<label>Фамилия:</label>
					<input type="text" name="last_name" value="<?php echo $user->get(UserField::LAST_NAME); ?>">
				</div>

				<div>
					<label>Дата рождения:</label>
					<input type="date" name="birthday_date" value="<?php echo $user->get(UserField::BIRTHDAY); ?>">
				</div>

				<div>
					<label>Закрыть профиль:</label>
					<input type="checkbox" name="is_private" value="1"
						<?php echo $user->get(UserField::PRIVATE) == 1 ? 'checked' : ''; ?>>
					<small>(Только друзья увидят ваши записи)</small>
				</div>

				<div>
					<label>Аватар:</label>
					<input type="file" name="avatar">
					<img src="../assets/uploads/<?php echo $user->get(UserField::AVATAR); ?>" width="200">
				</div>


				<button type="submit">Сохранить</button>
			</form>

			<div id="savingStatus"></div>
		</div>
		<?php 	include '../includes/menuRight.php'; ?>
	</div>

	<script src="../assets/js/saveSettings.js"></script>
</body>

</html>