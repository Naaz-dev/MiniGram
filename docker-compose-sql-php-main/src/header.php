<?php
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

    //kijk naar welke pagina je op zit en verander de header vervolgens
    $currentPage = basename($_SERVER['PHP_SELF']);
    $logged_in = $uid > 0;

    if ($currentPage != "login.php" && $currentPage != "signup.php") {
        if (!$logged_in) {
            echo ("
                <div class='header_gradient_border'>
                    <div class='header'>
                        <a class='nav_link' href='index.php'>MiniGram</a>
                        <a class='nav_link' href='signup.php'>Sign Up</a>
                        <a class='nav_link' href='login.php'>Login</a>
                    </div>  
                </div>
            ");
        } else {
            echo ("
                <div class='header_gradient_border'>
                    <div class='header'>
                        <a class='nav_link' href='index.php'>MiniGram</a>
                        <a class='nav_link' href='new_post.php'>New Post</a>
                        <a class='nav_link' href='profile.php'>Profile</a>
                        <a class='nav_link' href='logout.php'>Logout</a>
                    </div>                 
                </div>
            ");      
        }
    } else {
        echo ("
            <div class='header_gradient_border'>
                <div class='header'>
                    <a class='nav_link' href='index.php'>MiniGram</a>
                    <a class='nav_link' href='signup.php'>Sign Up</a>
                    <a class='nav_link' href='login.php'>Login</a>
                </div>              
            </div>
        ");    
    }    
?>
