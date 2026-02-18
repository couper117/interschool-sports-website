<?php include '../includes/header.php'; ?>
<?php
include '../config.php';
include '../auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="add_school.php">Add School</a> |
        <a href="add_team.php">Add Team</a> |
        <a href="add_player.php">Add Player</a> |
        <a href="create_fixture.php">Create Fixture</a> |
        <a href="enter_result.php">Enter Result</a> |
        <a href="post_announcement.php">Post Announcement</a> |
        <a href="upload_content.php">Upload Content</a> |
        <a href="../logout.php">Logout</a>
        <a href="list_schools.php">Manage Schools (Edit/Delete)</a> |
        <a href="change_password.php">Change My Password</a>
    </nav>
    <!-- You can add summary stats, recent activity, etc. here -->
</body>
</html>
<?php include 'includes/footer.php'; ?>
