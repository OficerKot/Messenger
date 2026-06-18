<?php 
session_start();
include '../includes/connectDB.php';
include '../classes/User.php';


$wall_owner = new User($_GET['user_id'], $conn);
$editableFields = $wall_owner->getEditableFields();
$profile_img = $wall_owner->get(UserField::AVATAR);?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/user_wall.css">
	<title><?php echo  $wall_owner->get(UserField::FIRST_NAME);?> <?php echo  $wall_owner->get(UserField::LAST_NAME);?>
	</title>
</head>

<body>
	<?php include '../includes/header.php'; ?>
	<div class="columns-container">

		<?php include '../includes/menuLeft.php'; ?>
		<div class="center-panel">
			<div class="center-panel1">

				<!-- Верхняя часть страницы-->
				<div class="userInfoContainer">
					<div style="display: flex; flex-direction: column;">
						<div class="userName">
							<?php echo $wall_owner->get(UserField::FIRST_NAME); ?>
							<?php echo $wall_owner->get(UserField::LAST_NAME); ?>
						</div>

						<div class="userLogin"> @<?php echo $wall_owner->get(UserField::LOGIN); ?></div>
						<div class="otherInfo">
							День рождения <?php echo $wall_owner->getFormattedBirthday();?>
							(<?php echo $wall_owner->getAge();?> лет)

						</div>
					</div>
					<img src="../assets/uploads/<?php echo $profile_img; ?>" style="height: 200px;">
				</div>

				<!--Форма для создания поста -->
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
		<?php 	include '../includes/menuRight.php'; ?>
	</div>
</body>

</html>