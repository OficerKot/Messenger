<?php
include 'connectDB.php';
$login = $_SESSION['login'];
$sql = "SELECT avatar FROM users WHERE login='$login'";
$result = $conn->query($sql);
$user_data = $result->fetch_assoc();		
?>