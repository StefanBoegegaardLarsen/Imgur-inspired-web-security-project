<?php
session_start();
// Getting the file login.php
require_once("login.php");

// Getting the user input values from index.php
$commentId = $_POST["commentId"];
// $csrf_token_delete = $_POST["csrf_token_delete"];

// Trying to log in, if unsuccesful, output "error"
try {
	$db = new PDO(
		"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
		"$user",
		"$pass");
} catch (PDOException $e) {
	echo "Error";
};

// Allow errors
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Try to execute query to get 'name' and 'pass' from database
if ($_SESSION["csrf_token_delete"] != $_POST["csrf_token_delete"] || !isset($_POST["csrf_token_delete"])) {
	echo '{"status":"securityIssue"}';
	exit;
} else {

	try {
		$query = ("DELETE FROM comments WHERE id_comments=:commentId");
		$sql = $db->prepare($query);

		$result = $sql->execute(array(
			":commentId" => $commentId
			));

	$row_count = $sql->rowCount(); // Adding the amount of rows to the variable row_count

	if ($row_count == 1) {
		echo '{"status": "ok"}';
	} else {
		echo '{"status": "idError"}';
	}


} catch (PDOException $e) {
	echo "Error";
};
}




