<?php

// Getting the file login.php
require_once("login.php");

$sLoginUserName = $_POST["name"];

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

try {
	$wrongAttempts = $db->prepare("UPDATE users SET attempts=0 WHERE name=:name");
	$wrongAttempts->bindParam(':name', $sLoginUserName);
	$wrongAttempts->execute();
	echo '{"status": "endTimeout"}';

} catch (PDOException $e) {
	echo '{"error":"invalid"}';
};



