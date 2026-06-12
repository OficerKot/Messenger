<?php 
session_start();
?>

<div class="left-panel">
	<h2>Меню</h2>
	<ul>
		<?php if(isset($_SESSION['id'])):
			$id = $_SESSION['id'];
			$user = new User($id, $conn);?>
		<li> <a href="../profile/userWall.php?user=<?php echo $user->get(UserField::LOGIN); ?>"
				style="text-decoration: none;">Профиль</a>
		</li>
		<li><a href="../profile/globalWall.php" style="text-decoration: none;">Лента</a></li>
		<li>Чат</li>
		<li>Звонки</li>
		<li>Друзья</li>
		<li><a href="../profile/settings.php" style="text-decoration: none;">Настройки</a></li>
		<?php else: ?>



		<div style="display: flex; flex-direction: column; gap:4px; padding: 4px;">
			<a href="../index.php?tab=login"
				style="background-color: lightgrey; border-radius: 10px; padding: 5px; text-decoration: none;">Вход</a>
			<a href="../index.php?tab=register"
				style="background-color: lightgray; border-radius: 10px; padding: 5px; text-decoration: none;">Регистрация</a>

		</div>


		<li>Лента</li>
		<?php endif;?>
	</ul>
</div>