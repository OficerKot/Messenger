<?php 
include "init.php";
?>

<div class="left-panel">
	<h2>Меню</h2>
	<ul>
		<?php if(isset($_SESSION['id'])):
			$id = $_SESSION['id'];
			$user = User::getUserById($id, $db)?>
		<li>
			<a href="../profile/userWall.php?user_id=<?php echo $user->get(UserField::ID); ?>">
				Профиль
			</a>
		</li>
		<li>
			<a href="../profile/globalWall.php" style="
				text-decoration: none;
				display: block;
				">
				Лента
			</a>
		</li>
		<li>
			<a href="../messages/messages.php" style="
				text-decoration: none;
				display: block;
				">
				Чат
			</a>
		</li>
		<li>
			<a href="../friends/friends.php" style="
				text-decoration: none;
				display: block;
				">
				Друзья
			</a>
		</li>
		<li>
			<a href="../profile/settings.php" style="
				text-decoration: none;
				display: block;
				">
				Настройки
			</a>
		</li>
		<?php else: ?>



		<div style="display: flex; flex-direction: column; gap:4px; padding: 4px;">
			<a href="../index.php?tab=login"
				style="background-color: lightgrey; border-radius: 10px; padding: 5px; text-decoration: none;">Вход</a>
			<a href="../index.php?tab=register"
				style="background-color: lightgray; border-radius: 10px; padding: 5px; text-decoration: none;">Регистрация</a>

		</div>


		<li>
			Лента
		</li>
		<?php endif;?>
	</ul>
</div>