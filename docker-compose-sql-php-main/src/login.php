<?php require_once "config.php";

$ok = ""; $err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT userid, username, password, profiel_image FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["userid"] = $user["userid"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["profiel_image"] = $user["profiel_image"];
            header("Location:/index.php");
            exit;
        } else { $err = "Invalid credentials."; }
    } else { $err = "User not found."; }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="header">
  <a href="index.php">MiniGram</a>
  <div class="navspace"></div>
  <a href="signup.php">Sign up</a>
</div>
<div class="container">
  <?php if ($err) echo '<div class="notice err">'.h($err).'</div>'; ?>
  <div class="form">
    <h2>Login</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</div>
</body>
</html>