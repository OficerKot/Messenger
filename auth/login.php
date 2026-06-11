<?php 
session_start();
include "../includes/connectDB.php";
$login = $_POST['login'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE login = '$login'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

if($result->num_rows == 0){
	echo("Ошибка: Пользователь с таким логином не найден.");
}
else{
$hasphass = $row['password'];

if(password_verify($password, $hasphass)){
	$_SESSION['login'] = $login;
	echo("Успешная авторизация.");
}
else{
	echo("Ошибка: Введён неверный пароль.");
}
}
?>