<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = prepare_stmt("INSERT INTO announcements (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    if ($stmt->execute()) {
        echo "<p style='color:green'>Announcement posted!</p>";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Post Announcement</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Post New Announcement</h2>
<form method="post">
    <label>Title:</label><input type="text" name="title" required><br><br>
    <label>Content:</label><br>
    <textarea name="content" rows="6" cols="50" required></textarea><br><br>
    <button type="submit">Post Announcement</button>
</form>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>