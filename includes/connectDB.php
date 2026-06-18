<?php
	/*$servername = "localhost";*/
	$servername = "messenger";
	$username = "root";
	$password = "";
	$dbname = "Social";

	$conn =  mysqli_connect($servername, $username, $password, $dbname);
	$conn -> set_charset("utf8"); 
	?>