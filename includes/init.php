<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Post.php';
require_once __DIR__ . '/../classes/Comment.php';

ini_set('display_errors', 0);    // Не показывать ошибки в браузере И НЕ ЗАСОРЯТЬ JSON!!
ini_set('log_errors', 1);   
$db = new Database();
?>