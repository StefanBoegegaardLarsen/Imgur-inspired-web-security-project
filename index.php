<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Group02</title>

	<?php include_once("linksAndScripts.php"); ?>
</head>

<body>

	<?php include_once("header.php"); ?>

	<div id="front-page-container">
		<div id="post-thumbs-container">

			<?php
			require_once("login.php");

			$db = new PDO(
				"mysql:host=$host;dbname=$db_name;charset=utf8mb4",
				"$user",
				"$pass");

			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $db->query("SELECT * FROM posts");

			foreach ($stmt as $row) {

				echo '
				<a class="thumb-container" href="post.php?id='.htmlentities($row['id_post']).'">
					<div class="thumb">
						<div class="thumb-img slide">
							<img src="/uploads/'.$row['image'].'" alt="'.htmlentities($row['title']).'"/>
							<div class="img-overlay">
								<p>'.htmlentities($row['title']).'</p>
							</div>
						</div>
					</div>
				</a>';
			}
			?>

		</div>
	</div> <!-- FRONT-PAGE-CONTAINER END -->

	<!-- MAIN -->
	<div id="main-content">
	</div> <!-- MAIN-CONTENT END -->

	<script src="scripts/login.js"></script>
	<script src="scripts/posts.js"></script>
</body>
</html>
