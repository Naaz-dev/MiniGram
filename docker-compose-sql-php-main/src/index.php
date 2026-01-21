<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>MiniGram Feed</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require_once "header.php";?>  

<div class="container">
    <?php if ($res && $res->num_rows > 0): ?>
    <?php while ($row = $res->fetch_assoc()): ?>
        <div class="content">
            <div class="left_side_container">
                <div class="profile_container">
                    <a class="profile_link" href="profile.php?userid=<?= (int)$row['userid']; ?>">
                        <?= h($row['username']); ?>
                    </a>
                    <img class="avatar" src="<?= h($row['profiel_image'] ?: 'assets/placeholder.jpg'); ?>" alt="avatar">                 
                </div>
                <div class="actions">
                    <?php if ($uid): ?>
                        <form action="like.php" method="POST">
                            <input type="hidden" name="postid" value="<?= (int)$row['postid']; ?>">
                            <button 
                                class="like_button <?= $row['is_liked'] ? 'liked' : 'unliked'; ?>" 
                                type="submit"
                            >
                            </button>
                        </form>
                    <?php endif; ?>
                    <p class="action_content">likes:</p>
                    <div class="action_content">
                        <?= (int)$row['like_count']; ?>
                    </div>
                    <p class="action_content">Posted at:</p>
                    <span class="action_content">
                        <?= h($row['created_at']); ?>
                    </span>     
                </div>                     
            </div>
            <div class="right_side_container">
                <div class="post_container">
                    <h3 class="caption"><?= h($row['caption']); ?></h3>     
                    <img class="post_image" src="<?= h($row['image_path']); ?>" alt="post image">
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
