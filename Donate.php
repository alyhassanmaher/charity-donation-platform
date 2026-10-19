<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $cardNumber = substr(trim($_POST["card-number"]), -4); 
    $amount = trim($_POST["amount"]);
    
    $date = date("Y-m-d H:i:s");

    if (!file_exists("donation_record.txt")) {
        touch("donation_record.txt");
        chmod("donation_record.txt", 0666);
    }

    if (is_writable("donation_record.txt")) {
        if ($file = fopen("donation_record.txt", "a")) {
            $data = "$name|$cardNumber|$amount|$date\n";
            
            if (fwrite($file, $data) === false) {
                die("Error: Could not write to file");
            }
            
            fclose($file);
            
            echo "<script>alert('Thank you for your donation! Your information has been saved.'); window.location.href='Home.php';</script>";
        } else {
            die("Error: Could not open file");
        }
    } else {
        die("Error: File is not writable");
    }
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
        <h6>Your support helps children in need</h6>
        <form method="post">
            <input type="text" name="name" placeholder="Name on Card" required class="input-field"><br>
            <input type="number" name="card-number" placeholder="Card Number" required class="input-field" 
                   pattern="[0-9]{16}" title="Please enter a valid 16-digit card number"><br>
            <input type="month" name="expiry" placeholder="Card Expiry Date" required class="input-field"><br>
            <input type="password" name="cvv" placeholder="CVV" required class="input-field" 
                   pattern="[0-9]{3,4}" title="Please enter a valid 3 or 4 digit CVV"><br>
            <input type="number" name="amount" placeholder="Amount ($)" required class="input-field" 
                   min="1" step="0.01"><br>
            <button type="submit" class="btn-submit">Make Donation</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
        </div>
    </div>
</div>
</body>
</html>
