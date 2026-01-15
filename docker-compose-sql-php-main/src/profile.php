<?php
require_once "config.php";
require_login();

$uid = (int)($_GET['userid'] ?? $_SESSION["userid"]); // if userid is provided, show that user's profile, else logged-in

// Get user info
$uStmt = $conn->prepare("SELECT username, bio, profiel_image, created_time FROM user WHERE userid = ?");
$uStmt->bind_param("i", $uid);
$uStmt->execute();
$user = $uStmt->get_result()->fetch_assoc();
$uStmt->close();

// Get posts for this user
$pStmt = $conn->prepare("SELECT postid, image_path, caption, created_at FROM post WHERE userid = ? ORDER BY postid DESC");
$pStmt->bind_param("i", $uid);
$pStmt->execute();
$posts = $pStmt->get_result();
$pStmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= h($user['username']); ?>'s Profile</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="header">
  <a href="index.php">MiniGram</a>
  <?php if ($_SESSION["userid"] == $uid): ?>
    <a href="new_post.php">New post</a>
    <div class="navspace"></div>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="index.php">Back to Feed</a>
  <?php endif; ?>
</div>

<div class="container">
  <div class="card">
    <div class="content">
      <div class="row">
        <img class="avatar" src="<?= h($user['profiel_image'] ?: 'assets/placeholder.jpg'); ?>" alt="avatar">
        <div>
          <strong><?= h($user['username']); ?></strong>
          <div class="meta">Joined: <?= h($user['created_time']); ?></div>
        </div>
      </div>
      <p><?= h($user['bio']); ?></p>
    </div>
  </div>

  <h3>Posts</h3>
  <?php if ($posts && $posts->num_rows): ?>
    <?php while ($p = $posts->fetch_assoc()): ?>
      <div class="card">
        <img src="<?= h($p['image_path']); ?>" alt="post">
        <div class="content">
          <strong><?= h($p['caption']); ?></strong>
          <div class="meta"><?= h($p['created_at']); ?></div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="notice">No posts yet.</div>
  <?php endif; ?>
</div>
</body>
</html>
