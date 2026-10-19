<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

$productsFile = "../data/products.txt";
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $description = trim($_POST['description']);

        if (!empty($name) && !empty($price)) {
            $product = "$name|$price|$description\n";
            file_put_contents($productsFile, $product, FILE_APPEND);
            $message = "Product added successfully!";
        }
    }
    elseif ($_POST['action'] === 'delete' && isset($_POST['product_id'])) {
        $lines = file($productsFile);
        unset($lines[$_POST['product_id']]);
        file_put_contents($productsFile, implode("", $lines));
        $message = "Product deleted successfully!";
    }
    elseif ($_POST['action'] === 'update') {
        $id = $_POST['product_id'];
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $description = trim($_POST['description']);

        $lines = file($productsFile);
        $lines[$id] = "$name|$price|$description\n";
        file_put_contents($productsFile, implode("", $lines));
        $message = "Product updated successfully!";
    }
}

$products = file_exists($productsFile) ? file($productsFile) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/MyStyle.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Products</h1>


        <div style="margin-bottom: 20px;">
            <a href="../admin.php" class="btn">← Back to Admin Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h2>Add New Product</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="number" name="price" placeholder="Price" step="0.01" required>
                <textarea name="description" placeholder="Description"></textarea>
                <button type="submit">Add Product</button>
            </form>
        </div>

        <div class="list-section">
            <h2>Current Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $id => $product): ?>
                        <?php $data = explode("|", trim($product)); ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data[0]); ?></td>
                            <td>$<?php echo htmlspecialchars($data[1]); ?></td>
                            <td><?php echo htmlspecialchars($data[2] ?? ''); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <button onclick="showEditForm(<?php echo $id; ?>, <?php echo json_encode($data[0]); ?>, <?php echo json_encode($data[1]); ?>, <?php echo json_encode($data[2] ?? ''); ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="editForm" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Edit Product</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    <input type="text" name="name" id="edit_name" required>
                    <input type="number" name="price" id="edit_price" step="0.01" required>
                    <textarea name="description" id="edit_description"></textarea>
                    <button type="submit">Update</button>
                    <button type="button" onclick="hideEditForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(id, name, price, description) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description;
        }

        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>