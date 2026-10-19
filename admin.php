<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: Home.php");
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

        <div class="admin-grid">
            <div class="admin-card">
                <h2>Products</h2>
                <ul>
                    <li><a href="admin/products_manage.php" target="_blank">Manage Products Data</a></li>
                    <li><a href="data/products.txt" target="_blank">View Products Data</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h2>Vaccines</h2>
                <ul>
                    <li><a href="admin/vaccines_manage.php" target="_blank">Manage Vaccines Data</a></li>
                    <li><a href="data/vaccines.txt" target="_blank">View Vaccines Data</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h2>Activities</h2>
                <ul>
                    <li><a href="admin/activities_manage.php" target="_blank">Manage Activities Data</a></li>
                    <li><a href="data/activities.txt" target="_blank">View Activities Data</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h2>Users & Orders</h2>
                <ul>
                    <li><a href="users.txt" target="_blank">View Registered Users</a></li>
                    <li><a href="data/orders.txt" target="_blank">View All Orders</a></li>
                    <li><a href="register.php">Add New User</a></li>
                </ul>
            </div>
        </div>

        <div class="admin-footer">
            <a href="Home.php" class="btn">Back to Home</a>
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html