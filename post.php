<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Web Security</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<!-- Implementation of jQuery UI -->
	<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

	<!-- Jasny Bootstrap CSS -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">

	<!-- Jasny Bootstrap JavaScript -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">

	<link href='https://fonts.googleapis.com/css?family=Gudea:400,700' rel='stylesheet' type='text/css'>

	<!-- Internal stylesheet -->
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>

	<?php include_once("header.php"); ?>

	<div id="post-container">
		<div id="post-body">

			<?php
			require_once("login.php");

			function generateRandomString($length = 20) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomString = '';

				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, strlen($characters) - 1)];
				}

				$salt = $randomString;
				return $salt;
			}

			$pepper = "#S4lT7Bu$1n3SS!";
			$randomString = generateRandomString();
			$token = hash("sha256", $pepper.$randomString);
			$_SESSION["csrf_token"] = $token;

			$pepper2 = "!s6Tq3Pp3R#";
			$randomString2 = generateRandomString();
			$token2 = hash("sha256", $pepper2.$randomString2);
			$_SESSION["csrf_token_delete"] = $token2;

			$id = $_GET['id'];

			$db = new PDO(
				"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
				"$user",
				"$pass");

			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = $db->prepare("SELECT * FROM posts WHERE id_post=:id");

			$result = $sql->execute(array(
				":id" => $id
				));

			foreach ($sql as $row) {
				echo '<h1 class="post-title">'.htmlentities($row['title']).'</h1>
				<p class="post-author">'.htmlentities($row['author']).'</p>
				<button class="btn-nav btn-left">Back</button>
				<button class="btn-nav btn-right">Next</button>
				<div class="post-content">
					<img class="post-img" src="uploads/'.htmlentities($row['image']).'"/>
					<p class="post-description">'.htmlentities($row['description']).'</p>
				</div>';
			}
			?>

			<input class="txt-post-comment" placeholder="Your comment goes here.." />
			<input type="hidden" name="csrf_token" class="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
			<button class="btn-add-comment">Add comment</button>
			<div class="comments-container">
				<?php
				require_once("login.php");
				$id = $_GET['id'];

				$db = new PDO(
					"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
					"$user",
					"$pass");

				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql = $db->prepare("SELECT * FROM comments WHERE id_post=:id");
				$result = $sql->execute(array(
					":id" => $id
					));
				// $commentRow = $sql->fetch(PDO::FETCH_ASSOC);

				$aname = "henning";
				$checka = $db->prepare("SELECT * FROM users WHERE name=:name");
				$result = $checka->execute(array(
					":name" => $aname
					));
				$row = $checka->fetch(PDO::FETCH_ASSOC);

				if (isset($_SESSION['username']) && $_SESSION['username'] == $aname) {
					foreach ($sql as $row) {
						echo '<div class="comment" id="'.$row['id_comments'].'">
						<p>'.htmlentities($row['username']).': '.htmlentities($row['comment']).'</p>
						<input type="hidden" name="csrf_token_delete" class="csrf_token_delete" value="'.$_SESSION['csrf_token_delete'].'">
						<button class="btnDeleteComment" id="'.$row['id_comments'].'">delete</button>
					</div>';
				}
			}else{
				foreach ($sql as $row) {
					echo '<div class="comment">
					<p>'.htmlentities($row['username']).': '.htmlentities($row['comment']).'</p>
				</div>';
			}
		}

		?>
	</div>
</div>
</div>

<script src="scripts/login.js"></script>
<script src="scripts/posts.js"></script>

</body>
</html>


