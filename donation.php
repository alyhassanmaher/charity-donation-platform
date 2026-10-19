<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = trim($_POST["amount"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    $date = date("Y-m-d");

    $file = fopen("donation_record.txt", "a");

    fwrite($file, "$name|$email|$amount|$date\n");

    fclose($file);

    echo "<script>alert('Donation recorded successfully!'); window.location.href='Home.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Make a Donation</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="js/header.js" defer></script>
    <script src="js/footer.js" defer></script>
</head>
<body>
<div class="content-container">
    <div class="login-container">
        <h2>Make a Donation</h2>
        <h6>Your donation helps children in need around the world</h6>
        <form method="post">
            <input type="number" name="amount" placeholder="Donation Amount ($)" required class="input-field" min="1"><br>
            <input type="text" name="name" placeholder="Your Name" required class="input-field"><br>
            <input type="email" name="email" placeholder="Your Email" required class="input-field"><br>
            <button type="submit" class="btn-submit">Make Donation</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
        </div>
    </div>
</div>
</body>
</html>
