<?php 
session_start();
$login = $_SESSION['login'];
?>

<div class="left-panel">
	<h2>Меню</h2>
	<ul>
		<?php if(isset($_SESSION['login'])):?>
		<li>Профиль</li>
		<li>Лента</li>
		<li>Чат</li>
		<li>Звонки</li>
		<li>Друзья</li>
		<li>Настройки</li>
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