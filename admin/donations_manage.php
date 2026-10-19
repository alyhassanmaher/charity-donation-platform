<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.php");
    exit();
}

if (isset($_POST['delete']) && isset($_POST['donation_id'])) {
    $donations = file("../donation_record.txt");
    $id = $_POST['donation_id'];
    if (isset($donations[$id])) {
        unset($donations[$id]);
        file_put_contents("../donation_record.txt", implode("", $donations));
    }
}

$donations = [];
if (file_exists("../donation_record.txt")) {
    $donations = file("../donation_record.txt");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Donations</title>
    <link rel="stylesheet" href="../assets/admin_style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Manage Donations</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Card Number (Last 4)</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $id => $donation): ?>
                    <?php $fields = explode("|", trim($donation)); ?>
                    <tr>
                        <td><?php echo $id + 1; ?></td>
                        <td><?php echo htmlspecialchars($fields[0]); ?></td>
                        <td>XXXX-XXXX-XXXX-<?php echo htmlspecialchars($fields[1]); ?></td>
                        <td>$<?php echo htmlspecialchars($fields[2]); ?></td>
                        <td><?php echo htmlspecialchars($fields[3]); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="donation_id" value="<?php echo $id; ?>">
                                <button type="submit" name="delete" class="btn-delete" onclick="return confirm('Are you sure you want to delete this donation record?')">Delete</button>
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
