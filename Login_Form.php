<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $file = fopen("users.txt", "r");
    $isValidUser = false;

    while (($line = fgets($file)) !== false) {
        list($storedUser, $storedPass) = explode("|", trim($line));

        if ($username == $storedUser && password_verify($password, $storedPass)) {
            $_SESSION["username"] = $username;
            fclose($file);
            header("Location: Home.php");
            exit();
        }
    }
    fclose($file);

    echo "<script>alert('Invalid username or password. Please try again.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="header.js" defer></script>
    <script src="footer.js" defer></script>
    <script src="scripts.js" defer></script>
</head>
<body>

<div class="content-container">
    <div class="login-container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p class="forgot-password">Don't have an account? <a href="register.php">Register here</a></p>
         <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
        </div>
    </div>
</div>

</body>
</html>
