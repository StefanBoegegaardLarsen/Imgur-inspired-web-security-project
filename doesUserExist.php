<?php
// Getting the file login.php
require_once("login.php");
require_once("SMSApi.php");

// Getting the user input values from index.php
$sCreateUserName = $_POST["name"];

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
try {
	$query = ("SELECT * FROM users WHERE name=:username");
	$sql = $db->prepare($query);

	$result = $sql->execute(array(
		":username" => $sCreateUserName
		));

	$rows = "";
	$rows = $sql->fetch(PDO::FETCH_ASSOC); // Getting the amount of rows returned by the query
	$row_count = $sql->rowCount(); // Adding the amount of rows to the variable row_count

	if ($row_count == 1) {
		echo '{"status": "userExists"}';
	} else {
		echo '{"status": "availableUsername"}';
	}

} catch (PDOException $e) {
	echo "Error";
};





