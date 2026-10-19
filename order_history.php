<?php
session_start();

if (!isset($_SESSION['username'])) {
    $error = "You must be logged in to view your order history.";
} else {
    $username = $_SESSION['username'];
    $orders = [];

    $orderFile = "data/orders.txt";
    if (file_exists($orderFile)) {
        $content = file_get_contents($orderFile);
        $blocks = explode("---", $content);

        foreach ($blocks as $block) {
            $lines = array_filter(array_map('trim', explode("\n", $block)));
            if (count($lines) < 2) continue;
            $header = array_shift($lines);
            $headerParts = explode("|", $header);
            if (count($headerParts) !== 3) continue;

            list($date, $user, $total) = $headerParts;

            if ($user !== $username) continue;

            $products = [];
            foreach ($lines as $line) {
                $parts = explode("|", $line);
                if (count($parts) === 4) {
                    list($product, $quantity, $price, $subtotal) = $parts;
                    $products[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ];
                }
            }

            $orders[] = [
                'date' => $date,
                'total' => $total,
                'products' => $products
            ];
        }
    } else {
        $error = "No orders found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Order History</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
</head>
<body>
<div class="content-container">
    <h2>Your Order History</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <p><a href="Login_Form.php">Login here</a></p>
    <?php else: ?>
        <?php if (empty($orders)): ?>
            <p>You have no orders yet.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <h3>Order Date: <?php echo htmlspecialchars($order['date']); ?></h3>
                <table border="1" cellpadding="10" cellspacing="0" style="margin-bottom: 30px;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price per unit</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['products'] as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>$<?php echo number_format((float)$product['price'], 2); ?></td>
                            <td>$<?php echo number_format((float)$product['subtotal'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format((float)$order['total'], 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="Home.php">Back to Home</a></p>
</div>
</body>
</html>
