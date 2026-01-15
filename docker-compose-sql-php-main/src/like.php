<?php
require_once "config.php";
require_login();

$postid = (int)($_POST["postid"] ?? 0);
$userid = (int)($_SESSION["userid"] ?? 0);

if ($postid > 0 && $userid > 0) {

    // Check if user already liked
    $stmt = $conn->prepare("SELECT id FROM `like` WHERE postid = ? AND userid = ?");
    $stmt->bind_param("ii", $postid, $userid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Unlike
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM `like` WHERE postid = ? AND userid = ?");
        $stmt->bind_param("ii", $postid, $userid);
        $stmt->execute();
    } else {
        // Like
        $stmt->close();
        $created = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO `like` (created_at, postid, userid) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $created, $postid, $userid);
        $stmt->execute();
    }

    $stmt->close();
}

header("Location: index.php");
exit;
?>

