<?php

require_once("login.php");
session_start();

$comment = $_POST['comment'];
$id = $_POST['id'];
$username = $_SESSION['username'];


try {
	$db = new PDO(
		"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
		"$user",
		"$pass");
} catch (PDOException $e) {
	echo "Error";
};

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if($comment != "") {
	if ($_SESSION["csrf_token"] != $_POST["csrf_token"] || !isset($_POST["csrf_token"])) {
		echo '{"status":"securityIssue"}';
		exit;
	} else {
		try {
			$stmt = $db->prepare("INSERT INTO comments(id_post, username, comment)
				VALUES(:id_post, :username, :comment)");
			$stmt->bindParam(':id_post', $id);
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':comment', $comment);

			$sql = $stmt->execute();

			if($sql) {
				echo '{"status":"commentAdded", "commenter":"'.htmlentities($username).'", "comment":"'.htmlentities($comment).'"}';
			} else {
				echo '{"status":"commentNOTAdded"}';
			}

		} catch (PDOException $e) {
			echo "Error";
		};
	}
}else {
				echo '{"status":"commentNOTAdded"}';
			}