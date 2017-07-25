<?php

session_start();

// Getting the file login.php
require_once("login.php");

// Trying to log in, if unsuccesful, output "error"
try {
	$db = new PDO(
		"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
		"$user",
		"$pass");
} catch (PDOException $e) {
	echo '{"error":"invalid"}';
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_SESSION['username'])) {
	echo '{"status":"sessionIsSet"}';
} else {
	echo '{"status":"notSet"}';
}

