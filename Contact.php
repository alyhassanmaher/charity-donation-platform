<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["first-name"]);
    $lastName = trim($_POST["last-name"]);
    $email = trim($_POST["email"]);
    $country = trim($_POST["country"]);
    
    $date = date("Y-m-d");

    $file = fopen("contact.txt", "a");
    
    fwrite($file, "$firstName|$lastName|$email|$country|$date\n");
    
    fclose($file);

    echo "<script>alert('Contact information submitted successfully!'); window.location.href='Home.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="js/header.js" defer></script>
    <script src="js/footer.js" defer></script>
</head>
<body>
<div class="content-container">
    <div class="login-container">
        <h2>Contact Us</h2>
        <h6>Please fill your information and we will contact you</h6>
        <form method="post">
            <input type="text" name="first-name" placeholder="First Name" required class="input-field"><br>
            <input type="text" name="last-name" placeholder="Last Name" required class="input-field"><br>
            <input type="email" name="email" placeholder="Email" required class="input-field"><br>
            <input type="text" name="country" placeholder="Country" required class="input-field"><br>
            <button type="submit" class="btn-submit">Submit</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
        </div>
    </div>
</div>
</body>
</html>
