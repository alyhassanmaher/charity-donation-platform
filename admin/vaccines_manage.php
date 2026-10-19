<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin-login.php");
    exit();
}

$vaccinesFile = "../data/vaccines.txt";
$message = '';
$vaccines = [];

if (file_exists($vaccinesFile)) {
    $lines = file($vaccinesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $index => $line) {
        list($name, $price, $description, $availability) = explode('|', $line);
        $vaccines[$index] = [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'availability' => $availability
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $new = [
            'name' => trim($_POST['name']),
            'price' => trim($_POST['price']),
            'description' => trim($_POST['description']),
            'availability' => trim($_POST['availability'])
        ];
        if (!empty($new['name']) && !empty($new['price'])) {
            $line = implode('|', $new) . "\n";
            file_put_contents($vaccinesFile, $line, FILE_APPEND);
            $message = "Vaccine added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']); exit();
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['vaccine_id'])) {
        unset($vaccines[$_POST['vaccine_id']]);
        $newLines = array_map(fn($v) => implode('|', $v) . "\n", $vaccines);
        file_put_contents($vaccinesFile, $newLines);
        $message = "Vaccine deleted successfully!";
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['vaccine_id'];
        $vaccines[$id] = [
            'name' => trim($_POST['name']),
            'price' => trim($_POST['price']),
            'description' => trim($_POST['description']),
            'availability' => trim($_POST['availability'])
        ];
        $newLines = array_map(fn($v) => implode('|', $v) . "\n", $vaccines);
        file_put_contents($vaccinesFile, $newLines);
        $message = "Vaccine updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Vaccines</title>
    <link rel="stylesheet" href="../assets/MyStyle.css">
</head>
<body>
<div class="admin-container">
    <h1>Manage Vaccines</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="form-section">
        <h2>Add New Vaccine</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Vaccine Name" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <textarea name="description" placeholder="Description"></textarea>
            <select name="availability" required>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
                <option value="Coming Soon">Coming Soon</option>
            </select>
            <button type="submit">Add Vaccine</button>
        </form>
    </div>

    <div class="list-section">
        <h2>Current Vaccines</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($vaccines as $id => $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['name']) ?></td>
                    <td>$<?= htmlspecialchars($v['price']) ?></td>
                    <td><?= htmlspecialchars($v['description']) ?></td>
                    <td><?= htmlspecialchars($v['availability']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="vaccine_id" value="<?= $id ?>">
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <button onclick="showEditForm('<?= $id ?>', '<?= addslashes($v['name']) ?>', '<?= addslashes($v['price']) ?>', '<?= addslashes($v['description']) ?>', '<?= addslashes($v['availability']) ?>')">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="editForm" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Edit Vaccine</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="vaccine_id" id="edit_vaccine_id">
                <input type="text" name="name" id="edit_name" required>
                <input type="number" name="price" id="edit_price" step="0.01" required>
                <textarea name="description" id="edit_description"></textarea>
                <select name="availability" id="edit_availability" required>
                    <option value="In Stock">In Stock</option>
                    <option value="Out of Stock">Out of Stock</option>
                    <option value="Coming Soon">Coming Soon</option>
                </select>
                <button type="submit">Update</button>
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="../admin.php" class="btn">Back to Dashboard</a>
    </div>
</div>

<script>
function showEditForm(id, name, price, description, availability) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_vaccine_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_availability').value = availability;
}
function hideEditForm() {
    document.getElementById('editForm').style.display = 'none';
}
</script>
</body>
</html>
