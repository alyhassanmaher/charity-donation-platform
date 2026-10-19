<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: Home.php");
    exit();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrator') {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Administrator Panel</title>
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>

<div class="content-container">
    <h1>Super Administrator Dashboard</h1>

    <p>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

    <h3>Administrator Tools</h3>
    <ul>
        <li><a href="users.txt" target="_blank">Manage All Users</a></li>
        <li><a href="data/orders.txt" target="_blank">View All Orders</a></li>
        <li><a href="admin.php">Go to Admin Panel</a></li>
        <li><a href="Home.php">Back to Homepage</a></li>
        <li><a href="admin_reports.php">View Donation Reports</a></li>
    </ul>

    <form method="post" style="margin-top: 20px;">
        <button type="submit" name="logout" class="btn-submit">Logout</button>
    </form>
</div>

</body>
</html>
