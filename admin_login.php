<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $lines = file('admin.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        list($storedUsername, $storedPassword, $role) = explode('|', trim($line));

        if ($username === $storedUsername && $password === $storedPassword) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role === 'admin') {
                header("Location: admin.php");
                exit();
            } elseif ($role === 'administrator') {
                header("Location: administrator.php");
                exit();
            }
        }
    }

    $_SESSION['login_error'] = "Invalid username or password.";
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <link rel="stylesheet" href="assets/style.css">

</head>
<body>

    <h2>Admin Login</h2>

    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color:red'>{$_SESSION['login_error']}</p>";
        unset($_SESSION['login_error']);
    }
    ?>

    <form method="POST" action="admin_login.php">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    
</body>
</html>
