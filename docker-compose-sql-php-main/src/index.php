<?php
require_once "config.php";

$uid = (int)($_SESSION["userid"] ?? 0);

// Prepare the SQL query using a placeholder for $uid
$sql = "
SELECT 
    p.postid,
    p.image_path,
    p.caption,
    p.created_at,
    u.userid,
    u.username,
    u.profiel_image,

    COUNT(l.id) AS like_count,
    CASE WHEN SUM(l.userid = ?) > 0 THEN TRUE ELSE FALSE END AS is_liked

FROM post p
JOIN user u ON p.userid = u.userid
LEFT JOIN `like` l ON l.postid = p.postid

GROUP BY 
    p.postid, p.image_path, p.caption, p.created_at,
    u.userid, u.username, u.profiel_image

ORDER BY p.postid DESC
LIMIT 50
";

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind the user id safely
$stmt->bind_param("i", $uid);

// Execute and get result
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>MiniGram Feed</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="header">
    <a href="index.php">MiniGram</a>

    <?php if ($uid): ?>
        <a href="new_post.php">New Post</a>
        <div class="navspace"></div>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <div class="navspace"></div>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
    <?php endif; ?>
</div>

<div class="container">

<?php if ($res && $res->num_rows > 0): ?>
    <?php while ($row = $res->fetch_assoc()): ?>
        <div class="card">

            <div class="content">
               <div class="row">
  <strong>
    <a href="profile.php?userid=<?= (int)$row['userid']; ?>">
      <?= h($row['username']); ?>
    </a>
  </strong>
  <img class="avatar" src="<?= h($row['profiel_image'] ?: 'assets/placeholder.jpg'); ?>" alt="avatar">
  <span class="meta"><?= h($row['created_at']); ?></span>
</div>


                </div>
            </div>

            <img src="<?= h($row['image_path']); ?>" alt="post image">

            <div class="content">
                <p><?= h($row['caption']); ?></p>

                <div class="actions">

                    <?php if ($uid): ?>
                        <form action="like.php" method="POST">
                            <input type="hidden" name="postid" value="<?= (int)$row['postid']; ?>">
                            <button type="submit">
                                <?= $row['is_liked'] ? "Unlike" : "Like"; ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <div class="like-count">
                        <?= (int)$row['like_count']; ?> likes
                    </div>

                </div>

            </div>

        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="notice">No posts yet. Be the first!</div>
<?php endif; ?>

</div>

</body>
</html>
