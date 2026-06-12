<?php 
session_start();
include '../includes/connectDB.php';
include '../classes/User.php';
if(!isset($_SESSION['id'])){ //защита от особо умных
	header('Location:../index.php');
	exit;
};

include '../includes/connectDB.php';
$user = new User($_SESSION['id'], $conn);
$editableFields = $user->getEditableFields();
?>


<!DOCTYPE html>
<html lang="en">

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
			Настройки пользователя <?php echo $user->get(UserField::LOGIN); ?>
			<form method="POST" action="save_settings.php" enctype="multipart/form-data">
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
					<label>Аватар:</label>
					<input type="file" name="avatar">
					<?php if($user->get(UserField::AVATAR)): ?>
					<img src="../assets/uploads/<?php echo $user->get(UserField::AVATAR); ?>" width="50">
					<?php endif; ?>
				</div>

				<button type="submit">Сохранить</button>
			</form>
		</div>
		<?php 	include '../includes/menuRight.php'; ?>
	</div>
</body>

</html>