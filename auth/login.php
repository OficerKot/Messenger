<?php 
session_start();
include "../includes/init.php";

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    echo "Ошибка: Все поля обязательны для заполнения";
    exit;
}

$sql = "SELECT * FROM users WHERE login = ?";
$user = $db->fetchOne($sql, [$login]);

if (!$user) {
    echo "Ошибка: Пользователь с таким логином не найден.";
    exit;
}

if (password_verify($password, $user['password'])) {
    $_SESSION['id'] = $user['user_id'];
    echo "Успешная авторизация.";
} else {
    echo "Ошибка: Введён неверный пароль.";
}
?>