<?php
session_start();
include "../includes/connectDB.php";
include "../classes/User.php";
$login = $_POST['login'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$birthday_date = $_POST['birthday_date'];

$sql = "SELECT * FROM users WHERE login = '$login'";
$result = $conn->query($sql);
if($result->num_rows > 0){
	echo("Ошибка: Вы ввели существующий логин! Если он ваш, авторизуйтесь");
}
else{
	$hashpass = password_hash($password, PASSWORD_BCRYPT);
	$creation_date = date('Y-m-d');
	$role = 0; // чтобы сделать кого то админом, редактируем через phpMyAdmin
	$avatar = 'baseimage.jpg';
	$sql = "INSERT INTO users (login, password, first_name, last_name, birthday_date, creation_date, role, is_private, avatar) 
	VALUES ('$login', '$hashpass', '$first_name', '$last_name', '$birthday_date', '$creation_date', '$role', 0, '$avatar')";
	
	$conn->query($sql);

	$user_id = $conn->insert_id; 
	$_SESSION['id'] = $user_id;

	$conn->close();

 
	echo("Пользователь $login успешно зарегистрирован");
}
?>