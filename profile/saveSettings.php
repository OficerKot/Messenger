<?php 
session_start();
include "../includes/connectDB.php";
include "../classes/User.php";
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$birthday_date = $_POST['birthday_date'] ?? '';
$is_private = isset($_POST['is_private']) ? 1 : 0;

$user = new User($_SESSION['id'], $conn);
$data = [
    UserField::FIRST_NAME => $first_name,
    UserField::LAST_NAME => $last_name,
    UserField::BIRTHDAY => $birthday_date,
	UserField::PRIVATE => $is_private
];



if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
	$file = $_FILES['avatar'];

	$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

	 if (in_array($ext, $allowed)) {
        $filename = 'user_' . $_SESSION['id'] . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], "../assets/uploads/$filename");
        $data[UserField::AVATAR] = $filename;
    }
}

if($user->update($data, $conn))
	echo("Настройки успешно сохранены");
else
	echo("Ошибка");

?>