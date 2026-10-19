<?php
session_start();

$message = '';
$productsFile = 'data/products.txt';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    if (!isset($_SESSION['username'])) {
        $message = "You must be logged in to add items to cart.";
    } else {
        $product_id = $_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity > 0) {
            $products = file($productsFile);
            if (isset($products[$product_id])) {
                $product_data = explode('|', trim($products[$product_id]));
                $_SESSION['cart'][] = [
                    'type' => 'product',
                    'name' => $product_data[0],
                    'price' => $product_data[1],
                    'quantity' => $quantity
                ];
                $message = "Product added to cart!";
            }
        }
    }
}

$products = file_exists($productsFile) ? file($productsFile) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Charity Products</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="js/header.js" defer></script>
    <script src="js/footer.js" defer></script>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .cart-info {
            text-align: right;
            padding: 10px;
            background: #f8f9fa;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cart-info">
            <a href="cart.php">
                Cart (<?php echo count($_SESSION['cart'] ?? []); ?> items)
            </a>
        </div>

        <h1>Available Products</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach ($products as $id => $product): ?>
                <?php $data = explode('|', trim($product)); ?>
                <div class="product-card">
                    <h2><?php echo htmlspecialchars($data[0]); ?></h2>
                    <p class="price">$<?php echo number_format((float)$data[1], 2); ?></p>
                    <p><?php echo htmlspecialchars($data[2] ?? ''); ?></p>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="add_to_cart">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <input type="number" name="quantity" value="1" min="1" required>
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
