<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Creator</title>
    <style>
        body {
            text-align: center;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        button {
            margin: 10px;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create a Post</h1>
        <button onclick="openPostPopup()">Create Post</button>
    </div>

    <script>
        function openPostPopup() {
            const postName = prompt("Enter Post Name:");
            if (postName) {
                localStorage.setItem("postName", postName);
                window.location.href = "post_page.php";
            }
        }
    </script>
</body>
</html>
