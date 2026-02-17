<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $type = (strpos($file['type'], 'image') !== false) ? 'image' : 'document';
    $filename = time() . "_" . basename($file['name']);
    $target = "../uploads/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $stmt = prepare_stmt("INSERT INTO uploads (filename, type) VALUES (?, ?)");
        $stmt->bind_param("ss", $filename, $type);
        $stmt->execute();
        echo "<p style='color:green'>File uploaded successfully!</p>";
    } else {
        echo "<p style='color:red'>Upload failed.</p>";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Upload Content</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Upload News Image or Document</h2>
<form method="post" enctype="multipart/form-data">
    <label>Select file (image or PDF):</label><br>
    <input type="file" name="file" accept="image/*,.pdf" required><br><br>
    <button type="submit">Upload</button>
</form>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>