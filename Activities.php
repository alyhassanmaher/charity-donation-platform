<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity = trim($_POST["activity"]);
    $date = trim($_POST["date"]);
    
    // Locations will be an array now
    $locations = isset($_POST["location"]) ? $_POST["location"] : [];

    // Sanitize each location and join into a string
    $locations = array_map('trim', $locations);
    $locations = array_map('htmlspecialchars', $locations);
    $locationString = implode(", ", $locations);

    $file = fopen("Activities.txt", "a");
    fwrite($file, "$activity|$date|$locationString\n");
    fclose($file);

    echo "<script>alert('Activity added successfully!'); window.location.href='Activities.php';</script>";
    exit();
}

$locations = ["Zayed", "October", "New Cairo", "Maadi", "Zamalek", "Mokattam", "Haram"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suggest Activities</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
    <script src="js/header.js" defer></script>
    <script src="js/footer.js" defer></script>
</head>
<body>
    <div class="content-container">
        <div class="login-container">
            <h2>Suggest Activities</h2>
            <h6>You suggest an activity and we will email you if we can find a slot that suits you</h6>
            
            <form method="post">
                <input type="text" name="activity" placeholder="Activity type" required class="input-field"><br>
                <input type="date" name="date" required class="input-field"><br>
                
                <div>
                    <label>Select Location(s):</label><br>
                    <?php foreach ($locations as $loc): ?>
                        <label>
                            <input type="checkbox" name="location[]" value="<?php echo htmlspecialchars($loc); ?>">
                            <?php echo htmlspecialchars($loc); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div><br>

                <button type="submit" class="btn-submit">Add Activity</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="Home.php"><button class="btn-submit">Go to Homepage</button></a>
            </div>
        </div>
    </div>
</body>
</html>
