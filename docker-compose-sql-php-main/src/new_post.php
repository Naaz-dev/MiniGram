<?php require_once "config.php"; require_login();

$ok = ""; $err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $err = "Please select an image.";
    } else {
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(16)) . "." . strtolower($ext);
        $dir = __DIR__ . "/uploaded_files/";
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $target = $dir . $name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $imgPath = "uploaded_files/" . $name;
            $caption = trim($_POST["caption"] ?? "");
            $created = now_string();
            $uid = $_SESSION["userid"];

            $stmt = $conn->prepare("INSERT INTO post (image_path, caption, created_at, userid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $imgPath, $caption, $created, $uid);
            if ($stmt->execute()) {
                $ok = "Post uploaded!";
            } else { $err = "Error: " . h($stmt->error); }
            $stmt->close();
        } else {
            $err = "Could not save image.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>New post</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<?php require_once "header.php";?> 
<div class="container">
  <?php if ($ok) echo '<div class="notice ok">'.h($ok).'</div>'; ?>
  <?php if ($err) echo '<div class="notice err">'.h($err).'</div>'; ?>
  <div class="form">
    <h2>Create a post</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="image" accept="image/*" required>
      <input type="text" name="caption" placeholder="Caption (max 45 chars)" maxlength="45">
      <button type="submit">Post</button>
    </form>
  </div>
</div>
</body>
</html>