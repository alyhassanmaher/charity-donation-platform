<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.php");
    exit();
}

if (isset($_POST['delete']) && isset($_POST['user_id'])) {
    $users = file("../users.txt");
    $id = $_POST['user_id'];
    if (isset($users[$id])) {
        unset($users[$id]);
        file_put_contents("../users.txt", implode("", $users));
    }
}

$users = [];
if (file_exists("../users.txt")) {
    $users = file("../users.txt");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/admin_style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Manage Users</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $id => $user): ?>
                    <?php $fields = explode("|", trim($user)); ?>
                    <tr>
                        <td><?php echo $id + 1; ?></td>
                        <td><?php echo htmlspecialchars($fields[0]); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                                <button type="submit" name="delete" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="admin-links">
            <a href="../administrator.php" class="btn-back">Back to Admin Panel</a>
        </div>
    </div>
</body>
</html>
