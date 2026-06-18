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
			Страница пользователя <?php echo $user->get(UserField::LOGIN); ?>
		</div>
		<?php 	include '../includes/menuRight.php'; ?>
	</div>
</body>

</html>