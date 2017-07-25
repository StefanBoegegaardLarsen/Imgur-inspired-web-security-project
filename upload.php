<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>File Upload</title>
  <?php include_once("linksAndScripts.php"); ?>
</head>
<body>
  <?php include_once("header.php"); ?>

  <div id="create-post-container">
    <form method="post" enctype="multipart/form-data" class="form-horizontal">

      <label>Title</label>
      <input type="title" name="title" required>
      <label>Description</label>
      <input type="description" name="description" required>
      <label class="control-label">Select .jpg or .png format image to upload</label>
      <input class="input-group" type="file" name="file" accept="image/*" required/>

      <button type="submit" name="btnsave" class="btn btn-default">save</button>
    </form>
  </div>

  <script src="scripts/login.js"></script>
  <script src="scripts/posts.js"></script>

</body>
</html>


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
      $_SESSION["upload_csrf_token"] = $token;

if(isset($_POST['btnsave'])) {
  //check if the upload fails according to settings in php.ini
  if(array_key_exists('file', $_FILES)){
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
      //echo 'upload was successful';
    } else {
      die("Upload failed with error code " . $_FILES['file']['error']);
    }
  }

  $imgFile = $_FILES['file']['name'];
  $tmp_dir = $_FILES['file']['tmp_name'];
  $imgSize = $_FILES['file']['size'];
  $author = $_SESSION['username'];
  $title = $_POST['title'];
  $description = $_POST['description'];

  if(empty($imgFile)){
    $errMSG = "Please Select Image File.";
  } else {
   $upload_dir = '/var/www/html/uploads/'; // upload directory

   $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

   // valid image extensions
   $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions

   // rename uploading image with date and time
   $date = date("Y-d-m_H-i-s");
   $userpic = $date.".".$imgExt;

   // allow valid image file formats
   if(in_array($imgExt, $valid_extensions)){
    // Check file size '5MB'
    if($imgSize < 5000000){
      move_uploaded_file($tmp_dir,$upload_dir.$userpic);
      // remove metadata
      switch ($imgExt) {
        case  'jpeg':
        $img = imagecreatefromjpeg ($upload_dir.$userpic);
        if (!$img) {
          $errMSG = "error: Corrupt file";
          echo $errMSG;
          die();
        }

        imagejpeg ($img, $upload_dir.$userpic, 100);
        imagedestroy ($img);
        break;

        case  'jpg':
        $img = imagecreatefromjpeg ($upload_dir.$userpic);
        // var_dump($upload_dir.$userpic);
        // die();
        if (!$img) {
          $errMSG = "error: Corrupt file";
          echo $errMSG;
          die();
        }
        imagejpeg ($img, $upload_dir.$userpic, 100);
        imagedestroy ($img);
        break;

        case  'png':
        $img = imagecreatefrompng($upload_dir.$userpic);
        if (!$img) {
          $errMSG = "error: Corrupt file";
          echo $errMSG;
          die();
        }
        imagepng ($img, $upload_dir.$userpic, 9);
        imagedestroy ($img);
        break;

        default:
        $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
        echo $errMSG;
        die();
        break;
      }

    }
    else {
      $errMSG = "Sorry, your file is too large.";
    }
  }
  else {
    $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
  }
}

  // if no error occured, continue ....
if(!isset($errMSG)) {

  try {
    $db = new PDO(
      "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
      "$user",
      "$pass");
  } catch (PDOException $e) {
    echo "error";
  };
  $test = "NotSet";

// Allow errors
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Try to execute query to get 'name' and 'pass' from database
  try {
    $stmt = $db->prepare('INSERT INTO posts(image, type, title, author, description)
      VALUES(:image, :type, :title, :author, :description)');
    $stmt->bindParam(':image', $userpic);
    $stmt->bindParam(':type', $imgExt);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':description', $description);

    if($stmt->execute()) {
      try {
        $successMSG = "new record succesfully inserted ...";
        $stmt = ('SELECT * FROM posts ORDER BY id_post DESC LIMIT 1');
        $sql = $db->prepare($stmt);
        $result = $sql->execute();
        $row = "";
        $rows = $sql->fetch(PDO::FETCH_ASSOC);
        $lastId = $rows["id_post"];

        header("refresh:2;post.php?id=".$lastId); // redirects image view page after 2 seconds.
      } catch (Exception $e) {
        echo '{"status":"Something went wrong"}';
      }
    } else
    {
      $errMSG = "error while inserting....";
    }
  } catch (PDOException $e) {
    echo "error";
  };
} else {
  echo $errMSG;
}

}

?>