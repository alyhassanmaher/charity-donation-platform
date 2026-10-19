<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $item_type = $_POST['item_type']; 
        $item_id = $_POST['item_id'];
        $quantity = (int)$_POST['quantity'];
        
        
        $file = $item_type === 'product' ? 'data/products.txt' : 'data/vaccines.txt';
        if (file_exists($file)) {
            $items = file($file);
            if (isset($items[$item_id])) {
                $item_data = explode('|', trim($items[$item_id]));
                $_SESSION['cart'][] = [
                    'type' => $item_type,
                    'name' => $item_data[0],
                    'price' => $item_data[1],
                    'quantity' => $quantity
                ];
                $message = "Item added to cart!";
            }
        }
    }
    elseif ($_POST['action'] === 'remove' && isset($_POST['cart_index'])) {
        $index = $_POST['cart_index'];
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $message = "Item removed from cart!";
        }
    }
    elseif ($_POST['action'] === 'checkout') {
        if (!empty($_SESSION['cart'])) {
            $order = '';
            $total = 0;
            
            foreach ($_SESSION['cart'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $order .= "{$item['name']}|{$item['quantity']}|{$item['price']}|{$subtotal}\n";
            }
            
            $orderFile = 'data/orders.txt';
            $orderData = date('Y-m-d H:i:s') . "|" . $_SESSION['username'] . "|" . $total . "\n" . $order . "---\n";
            file_put_contents($orderFile, $orderData, FILE_APPEND);
            
            $_SESSION['cart'] = [];
            $message = "Order placed successfully!";
        }
    }
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
</head>
<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="checkout-section">
                <form method="POST">
                    <input type="hidden" name="action" value="checkout">
                    <button type="submit" class="checkout-btn">Checkout</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="navigation">
            <a href="products.php">Continue Shopping</a>
            <a href="Home.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
