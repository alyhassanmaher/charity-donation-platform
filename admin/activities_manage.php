<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

$activitiesFile = "../data/activities.txt";
$message = '';

$activities = [];
if (file_exists($activitiesFile)) {
    $lines = file($activitiesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $index => $line) {
        list($name, $date, $location, $description) = explode('|', $line);
        $activities[$index] = [
            'name' => $name,
            'date' => $date,
            'location' => $location,
            'description' => $description
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $newActivity = [
            'name' => trim($_POST['name']),
            'date' => trim($_POST['date']),
            'location' => trim($_POST['location']),
            'description' => trim($_POST['description'])
        ];
        if (!empty($newActivity['name']) && !empty($newActivity['date'])) {
            $line = implode('|', $newActivity) . "\n";
            file_put_contents($activitiesFile, $line, FILE_APPEND);
            $message = "Activity added successfully!";
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['activity_id'])) {
        unset($activities[$_POST['activity_id']]);
        $linesToWrite = array_map(function ($activity) {
            return implode('|', $activity);
        }, $activities);
        file_put_contents($activitiesFile, implode("\n", $linesToWrite) . "\n");
        $message = "Activity deleted successfully!";
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['activity_id'];
        $activities[$id] = [
            'name' => trim($_POST['name']),
            'date' => trim($_POST['date']),
            'location' => trim($_POST['location']),
            'description' => trim($_POST['description'])
        ];
        $linesToWrite = array_map(function ($activity) {
            return implode('|', $activity);
        }, $activities);
        file_put_contents($activitiesFile, implode("\n", $linesToWrite) . "\n");
        $message = "Activity updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Activities</title>
    <link rel="stylesheet" href="../assets/MyStyle.css">
</head>
<body>
<div class="admin-container">
    <h1>Manage Activities</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="form-section">
        <h2>Add New Activity</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Activity Name" required>
            <input type="date" name="date" required>
            <input type="text" name="location" placeholder="Location" required>
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit">Add Activity</button>
        </form>
    </div>

    <div class="list-section">
        <h2>Current Activities</h2>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($activities as $id => $activity): ?>
                <tr>
                    <td><?php echo htmlspecialchars($activity['name']); ?></td>
                    <td><?php echo htmlspecialchars($activity['date']); ?></td>
                    <td><?php echo htmlspecialchars($activity['location']); ?></td>
                    <td><?php echo htmlspecialchars($activity['description']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="activity_id" value="<?php echo $id; ?>">
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <button onclick="showEditForm('<?php echo $id; ?>', '<?php echo addslashes($activity['name']); ?>', '<?php echo $activity['date']; ?>', '<?php echo addslashes($activity['location']); ?>', '<?php echo addslashes($activity['description']); ?>')">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="editForm" class="modal" style="display:none;">
        <div class="modal-content">
            <h2>Edit Activity</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="activity_id" id="edit_activity_id">
                <input type="text" name="name" id="edit_name" required>
                <input type="date" name="date" id="edit_date" required>
                <input type="text" name="location" id="edit_location" required>
                <textarea name="description" id="edit_description"></textarea>
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
    function showEditForm(id, name, date, location, description) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('edit_activity_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_location').value = location;
        document.getElementById('edit_description').value = description;
    }

    function hideEditForm() {
        document.getElementById('editForm').style.display = 'none';
    }
</script>
</body>
</html>
