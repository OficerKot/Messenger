<?php
$activeTab = isset($_GET['tab'])? $_GET['tab'] : null;
?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/auth_page.css">
	<title>Welcome</title>
</head>

<body>
	<div id="welcome">
		<h1>Добро пожаловать!</h1>
		<div class="auth-window">
			<div style="display: flex; flex-direction: row; gap: 8px;">
				<button id="btn-auth" class="auth-button" onclick="show_auth()">
					Войти
				</button>

				<button id="btn-reg" class="auth-button" onclick="show_reg()">
					Зарегистрироваться
				</button>
			</div>

			<a href="profile/globalWall.php">Продолжить как гость</a>

			<div id="authStatus"></div>
		</div>
	</div>

	<div id="login-window" class="login-window" style="visibility: hidden;">
		<form action="" method="post" id="loginForm">
			<div style="display: flex; flex-direction: column;">
				<label for="login">Логин</label>
				<input type="text" name="login">
				<label for="password">Пароль</label>
				<input type="password" name="password">
			</div>

			<button type="submit">Вход</button>
		</form>

	</div>

	<div id="register-window" style="visibility: hidden">
		<form action="" method="post" id="registForm">
			<div style="display: flex; flex-direction: column;">
				<label for="login">Логин</label>
				<input type="text" name="login" id="login">
				<label for="password">Пароль</label>
				<input type="password" name="password" id="password">
				<label for="first_name">Имя</label>
				<input type="text" name="first_name" id="first_name">
				<label for="last_name">Фамилия</label>
				<input type="text" name="last_name" id="last_name">
				<label for="birthday_date">Дата рождения</label>
				<input type="date" name="birthday_date" id="birthday_date">
			</div>

			<button type="submit">Регистрация</button>
		</form>
	</div>


</body>

<script src="assets/js/script.js"></script>
<script src="assets/js/login.js"></script>
<script src="assets/js/regist.js"></script>

<script>
<?php if ($activeTab == 'register'): ?>
show_reg();
<?php elseif ($activeTab == 'login'): ?>
show_auth();
<?php endif; ?>
</script>

</html>