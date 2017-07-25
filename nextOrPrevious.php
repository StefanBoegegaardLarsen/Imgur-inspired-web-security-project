<?php

// Getting the file login.php
require_once("login.php");

$currentId = $_POST['currentId'];
$keyPressed = $_POST['keyPressed'];

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

if ($keyPressed == "left"){
	try {
		$stmt = ("SELECT * FROM posts WHERE id_post < :currentId ORDER BY id_post DESC LIMIT 1");
		$sql = $db->prepare($stmt);

		$result = $sql->execute(array(
			":currentId" => $currentId
			));

		$rows = "";
		$rows = $sql->fetch(PDO::FETCH_ASSOC);
		$row_count = $sql->rowCount();

		if ($row_count > 0) {
			$previousId = $rows['id_post'];
			echo '{"status": "ok", "id": "'.htmlentities($previousId).'"}';
		} else {
			echo '{"status":"error"}';
		}

	} catch (PDOException $e) {
		echo '{"error":"invalid"}';
	};
} else {
	try {
		$stmt = ("SELECT * FROM posts WHERE id_post > :currentId ORDER BY id_post LIMIT 1");
		$sql = $db->prepare($stmt);

		$result = $sql->execute(array(
			":currentId" => $currentId
			));

		$rows = "";
		$rows = $sql->fetch(PDO::FETCH_ASSOC);
		$row_count = $sql->rowCount();

		if ($row_count > 0) {
			$nextId = $rows['id_post'];
			echo '{"status": "ok", "id": "'.htmlentities($nextId).'"}';
		} else {
			echo '{"status":"error"}';
		}

	} catch (PDOException $e) {
		echo '{"error":"invalid"}';
	};
}

