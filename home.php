<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image: url('l.jpg') ;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            width: 100%;
        }
        
        header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        
        header h1 {
            margin: 0;
        }
        
        header div {
            margin-top: 10px;
        }
        
        button {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }
        h2{
            color: white;
        }
        button:hover {
            background-color: greenyellow;
        }
        
        button a {
            text-decoration: none;
            color: black;
        }
        
        .post {
            width: 60%;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to My Website</h1>
        <div>
            <button id="loginBtn"><a href="login.php">Login</a></button>
            <button id="signupBtn"><a href="signup.php">Sign Up</a></button>
        </div>
    </header>
    <h2>Top 5 Posts</h2>

    <?php
    $postsFile = 'posts.json';

    if (file_exists($postsFile)) {
        $posts = json_decode(file_get_contents($postsFile), true) ?? [];

        // Sort posts by likes in descending order
        usort($posts, function ($a, $b) {
            return $b['likes'] - $a['likes'];
        });

        // Get the top 5 posts
        $topPosts = array_slice($posts, 0, 5);

        foreach ($topPosts as $post) {
            echo "<div class='post'>";
            
            // Use null coalescing operator to prevent undefined key errors
            $title = $post['title']??"No Title Available";
            $likes = $post['likes'] ?? 0;

            echo "<h3>" . htmlspecialchars($title) . "</h3>";
            echo "<p><strong>Likes:</strong> " . htmlspecialchars($likes) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No posts available.</p>";
    }
    ?>
</body>
</html>

