<?php

session_start();

// Getting the file login.php
require_once("login.php");

$sLoginUserName = $_POST["name"];
$sLoginUserPass = $_POST["pass"];

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

$userSuspended;

try {
	$stmt = $db->prepare("SELECT attempts, timeoutStamp FROM users WHERE name=:name");
	$stmt->bindParam(':name', $sLoginUserName);
	$stmt->execute();

	$rows = "";
	$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	$row_count = $stmt->rowCount();

	$attempts = $rows['attempts'];
	$timestamp = $rows['createdTimestamp'];
	$currentTimestamp = date("Y-m-d H:i:s");

	$timeDifference = $currentTimestamp - $timestamp;

	if ($timeDifference >= 150 && $row_count == 1){
		try {
			// $stmt = $db->prepare("UPDATE users SET attempts=0 WHERE name='".$sLoginUserName."'");
			// $stmt->execute();

			$loginPass = ("SELECT * FROM users WHERE name=:username");
			$sql = $db->prepare($loginPass);

			$result = $sql->execute(array(
				":username" => $sLoginUserName
				));

			$rows = "";
			$rows = $sql->fetch(PDO::FETCH_ASSOC);
			$row_count = $sql->rowCount();

			$passFromDb = $rows['pass'];
			$saltAndPass = $rows['salt'];
			$salt = $saltAndPass.$sLoginUserPass;
			$hashed = hash('sha512', $salt);

			$attemptsFromDb = $rows['attempts'];

			if ($row_count == 1 && $passFromDb == $hashed) {
				$_SESSION['username'] = $sLoginUserName;
				echo '{"status": "loggedIn", "username":"'.htmlentities($sLoginUserName).'"}';
			} else {
				if ($attemptsFromDb <= 1){
					$wrongAttempts = $db->prepare("UPDATE users SET attempts=attempts+1 WHERE name=:name");
					$wrongAttempts->bindParam(':name', $sLoginUserName);
					$wrongAttempts->execute();

					$createdTimestamp = date("Y-m-d H:i:s");
					$timestamp = $db->prepare("UPDATE users SET timeoutStamp=:timeoutStamp WHERE name=:name");
					$timestamp->bindParam(':timeoutStamp', $createdTimestamp);
					$timestamp->bindParam(':name', $sLoginUserName);
					$timestamp->execute();
					echo '{"status": "wrongCredentials"}';
				} else if ($attemptsFromDb >= 2) {
					$userSuspended = true;
					echo '{"status": "timeout"}';
				} else {
					echo '{"status": "error"}';
				}
			}
		} catch (PDOException $e) {
			echo '{"error":"invalid"}';
		};
	} else {
		echo '{"status": "timeout"}';
	}

} catch (PDOException $e) {
	echo '{"error":"invalid"}';
}
