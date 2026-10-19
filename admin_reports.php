<?php
session_start();

$dataFile = 'data/orders.txt';
$monthlyTotals = [];
$yearlyTotals = [];

if (file_exists($dataFile)) {
    $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
    $parts = explode('|', $line);
    if (count($parts) !== 3) continue;

    $date = $parts[0];
    $total = (float) $parts[2];

    $timestamp = strtotime($date);
    $month = date('Y-m', $timestamp);
    $year = date('Y', $timestamp);

    if (!isset($monthlyTotals[$month])) {
        $monthlyTotals[$month] = 0;
    }
    $monthlyTotals[$month] += $total;

    if (!isset($yearlyTotals[$year])) {
        $yearlyTotals[$year] = 0;
    }
    $yearlyTotals[$year] += $total;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Reports</title>
    <link rel="stylesheet" href="assets/MyStyle.css">
</head>
<body>
    <div class="content-container">
        <h2>Donation Reports</h2>

        <h3>Total Donations Per Month</h3>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Month</th>
                <th>Total Donations</th>
            </tr>
            <?php foreach ($monthlyTotals as $month => $total): ?>
            <tr>
                <td><?php echo $month; ?></td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total Donations Per Year</h3>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Year</th>
                <th>Total Donations</th>
            </tr>
            <?php foreach ($yearlyTotals as $year => $total): ?>
            <tr>
                <td><?php echo $year; ?></td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>