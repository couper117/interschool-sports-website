<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $cell = $_POST['cell'];
    $sector = $_POST['sector'];
    $district = $_POST['district'];
    $province = $_POST['province'];

    $stmt = prepare_stmt("INSERT INTO schools (name, cell, sector, district, province) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $cell, $sector, $district, $province);
    if ($stmt->execute()) {
        echo "<p style='color:green'>School added successfully!</p>";
    } else {
        echo "<p style='color:red'>Error adding school.</p>";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Add School</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Add New School</h2>
<form method="post">
    <label>School Name:</label><input type="text" name="name" required><br><br>
    <label>Cell:</label><input type="text" name="cell" required><br><br>
    <label>Sector:</label><input type="text" name="sector" required><br><br>
    <label>District:</label><input type="text" name="district" required><br><br>
    <label>Province:</label><input type="text" name="province" required><br><br>
    <button type="submit">Add School</button>
</form>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>