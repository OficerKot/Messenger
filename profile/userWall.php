<?php 
include "../includes/init.php";

$wall_owner = User::getUserById($_GET['user_id'], $db);
	$editableFields = $wall_owner? $wall_owner->getEditableFields(): [];
	$profile_img = $wall_owner? $wall_owner->get(UserField::AVATAR): 'baseimage.jpg';
	$first_name = $wall_owner? $wall_owner->get(UserField::FIRST_NAME): 'Пользователь';
	$last_name = $wall_owner? $wall_owner->get(UserField::LAST_NAME): 'Несуществующий';	
	$login = $wall_owner? $wall_owner->get(UserField::LOGIN): 'does_not_exist';
	
?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/user_wall.css">
	<title><?php echo $first_name ?> <?php echo $last_name ?>
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
							<?php echo $first_name ?>
							<?php echo $last_name ?>
						</div>

						<div class="userLogin"> @<?php echo $login ?></div>

						<?php if($wall_owner != null){?>
						<div class="otherInfo">
							День рождения <?php echo $wall_owner->getFormattedBirthday();?>
							(<?php echo $wall_owner->getAge();?> лет)

						</div>
						<?php } ?>
					</div>
					<img src="../assets/uploads/<?php echo $profile_img; ?>" style="height: 200px;">
				</div>

				<?php if($wall_owner && isset($_SESSION['id']) && AccessHelper::HasAccessToWall($_SESSION['id'], $_GET['user_id'], $db)){?>
				<!--Форма для создания поста -->
				<div>
					<form id="postForm" class="postForm">
						<textarea id="postMessage" placeholder="Что у вас нового?"></textarea>
						<input id="postImage" type="file">
						<button>Поделиться</button>
					</form>
				</div>
				<?php } 
				if ($wall_owner != null){?>

				<!-- Посты -->
				<div style="width:100%; display: flex; justify-content: center; align-items: center;">
					<h3>Все посты</h3>
				</div>
				<div class="postsContainer" id="postsContainer">

				</div>
				<?php } else {?>
				<div class="pageNotFound">
					Как вы тут оказались?
				</div>
				<?php } ?>
			</div>
		</div>
		<?php 	include '../includes/menuRight.php'; ?>

		<script src="../assets/js/postView.js"></script>
		<script src="../assets/js/postApi.js"></script>

		<script src="../assets/js/comments/CommentView.js"></script>
		<script src="../assets/js/comments/commentApi.js"></script>

		<script src="../assets/js/userWall.js"></script>
	</div>
</body>

</html>