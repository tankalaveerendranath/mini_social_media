<?php
session_start();

$jsonFile = 'users.json';

// Load existing users
$users = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($users[$email]) && password_verify($password, $users[$email]['password'])) {
        $_SESSION['user'] = $email; // Store session
        header("Location: post_page.php");
        exit();
    } else {
        echo "Invalid email or password. <a href='signup.php'>Signup here</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Login Page</title>
   <style>
body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url('red.jpg') ;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            width: 100%;
    margin: 0;
}

.login-container {
    background-color: #000;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    text-align: center;
    position: relative;
    color: white;
    height:400px;
}

.login-container::before {
    content: "";
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    background: linear-gradient(90deg, red, yellow, green, cyan, blue, magenta, red);
    z-index: -1;
    border-radius: 15px;
    animation: led-border 3s linear infinite;
}

@keyframes led-border {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
}
hr{
    background-color:white;
    height:2px;
}
input {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    outline: none;
    border-radius: 5px;
}

button {
    width: 50%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color:red;
}

.error {
    color: red;
    font-weight: bold;
}
.one{
    display:flex;
    padding:10px;
    padding-bottom:0px;
}
.one i{
    padding-top:20px;
}
.two{
    display:flex;
    padding:10px;
}
.two i{
    padding-top:20px;
}
a{
    text-decoration: none;
    color: crimson;
}
a:hover{
    color:white;
}
</style>
</head>
<body>
    <div class="login-container">
        <h2>LOGIN</h2><hr><br><br>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
        <div class="one"><i class="fas fa-envelope"></i>&nbsp;&nbsp;
            <input type="email" name="email" placeholder="Enter mail" required></div>
            <div class="two"><i class="fas fa-lock"></i>&nbsp;&nbsp;
            <input type="password" name="password" placeholder="Password" required><br><br><br></div><br>
            <button type="submit">Login</button>
        </form>
        <br>
        <br>
        <a href="signup.php">Don't have an account? Signup here</a>
    </div>
</body>
</html>

