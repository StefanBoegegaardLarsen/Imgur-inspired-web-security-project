<?php
// Getting the file login.php
require_once("login.php");
require_once("SMSApi.php");

// Getting the user input values from index.php
$sCreateUserName = $_POST["name"];
$sCeateUserPass = $_POST["pass"];

function generateRandomString($length = 20) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	$salt = $randomString;
	return $salt;
}

$randomString = generateRandomString();
$saltAndPass = $randomString.$sCeateUserPass;
$hashed = hash("sha512", $saltAndPass);

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
		try {
			$sql = $db->prepare("INSERT INTO users (name, pass, salt)
				VALUES (:name, :pass, :salt)");
			$sql->bindParam(':name', $sCreateUserName);
			$sql->bindParam(':pass', $hashed);
			$sql->bindParam(':salt', $randomString);

			$sql->execute();

			echo '{"status": "userCreated"}';
		} catch (PDOException $e) {
			echo "Error";
		};
	}

} catch (PDOException $e) {
	echo "Error";
};





