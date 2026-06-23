<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Post.php';
require_once __DIR__ . '/../classes/Message.php';
require_once __DIR__ . '/../classes/Comment.php';
require_once __DIR__ . '/../handlers/accessManager.php';
require_once __DIR__ . '/../classes/NotificationManager.php';
  
$db = new Database();