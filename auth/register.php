<?php
session_start();
include "../includes/init.php";

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$birthday_date = $_POST['birthday_date'] ?? '';

// Проверка на пустые поля
if (empty($login) || empty($password) || empty($first_name) || empty($last_name)) {
    echo "Ошибка: Все поля обязательны для заполнения";
    exit;
}

// Проверка существующего логина
$sql = "SELECT * FROM users WHERE login = ?";
$existing = $db->fetchOne($sql, [$login]);

if ($existing) {
    echo "Ошибка: Пользователь с таким логином уже существует!";
    exit;
}

$hashpass = password_hash($password, PASSWORD_BCRYPT);
$creation_date = date('Y-m-d');

$data = [
    'login' => $login,
    'password' => $hashpass,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'birthday_date' => $birthday_date,
    'creation_date' => $creation_date,
    'role' => 0,
	'is_private' => 0,
    'avatar' => 'baseimage.jpg'
];

$user_id = $db->insert('users', $data);

if ($user_id) {
    $_SESSION['id'] = $user_id;
    echo "Пользователь $login успешно зарегистрирован";
} else {
    echo "Ошибка при регистрации";
}
?>