<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (file_exists("users.txt")) {
        $file = fopen("users.txt", "r");
        
        while (($line = fgets($file)) !== false) {
            list($storedUser, $storedPass) = explode("|", trim($line));
            
            if ($username == $storedUser) {
                fclose($file);
                echo "<script>alert('Username already taken. Please choose a different one.'); window.location.href='register.php';</script>";
                exit(); 
            }
        }
        fclose($file);
    }

    $file = fopen("users.txt", "a");
    fwrite($file, "$username|$password\n");
    fclose($file);

    $_SESSION["username"] = $username;

    echo "<script>alert('Registration successful! Redirecting to homepage.'); window.location.href='Home.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="header.js" defer></script>
    <script src="footer.js" defer></script>
</head>
<body>
<div class="content-container">
    <div class="login-container">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required class="input-field"><br>
            <input type="password" name="password" placeholder="Enter Password" required class="input-field"><br>
            <button type="submit" class="btn-submit">Register</button>
        </form>
        <p class="forgot-password">Already have an account? <a href="Login_Form.php">Login here</a></p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
        </div>
    </div>
</div>
</body>
</html>