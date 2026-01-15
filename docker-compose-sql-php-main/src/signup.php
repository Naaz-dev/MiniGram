<?php require_once "config.php";

$ok = ""; $err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $bio = trim($_POST["bio"] ?? "");
    // profile image optional upload
    $profilePath = null;

    if (isset($_FILES["profiel_image"]) && $_FILES["profiel_image"]["error"] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES["profiel_image"]["name"], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(16)) . "." . strtolower($ext);
        $targetDir = __DIR__ . "/uploaded_files/";
        $target = $targetDir . $name;
        if (!is_dir($targetDir)) { mkdir($targetDir, 0755, true); }
        if (move_uploaded_file($_FILES["profiel_image"]["tmp_name"], $target)) {
            $profilePath = "uploaded_files/" . $name;
        }
    }

    if ($username === "" || $password === "") {
        $err = "Username and password are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $created = now_string();
        $stmt = $conn->prepare("INSERT INTO user (username, password, bio, profiel_image, created_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hash, $bio, $profilePath, $created);
        if ($stmt->execute()) {
            $ok = "Account created. Please log in.";
        } else {
            $err = "Signup failed: " . h($stmt->error);
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Sign up</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="header">
  <a href="index.php">MiniGram</a>
  <div class="navspace"></div>
  <a href="login.php">Login</a>
</div>
<div class="container">
  <?php if ($ok) echo '<div class="notice ok">'.h($ok).'</div>'; ?>
  <?php if ($err) echo '<div class="notice err">'.h($err).'</div>'; ?>
  <div class="form">
    <h2>Create account</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="username" placeholder="Username" maxlength="20" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="file" name="profiel_image" accept="image/*">
      <textarea name="bio" placeholder="Bio (optional)" maxlength="45"></textarea>
      <button type="submit">Sign up</button>
    </form>
  </div>
</div>
</body>
</html>