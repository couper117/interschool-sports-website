<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Admin Login</h2>
    <form action="login_process.php" method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <a href="../logout.php">Logout</a>
</body>
</html>
<?php 
if (isset($_GET['msg']) && $_GET['msg'] === 'password_changed') {
    echo '<p style="color:green; text-align:center;">Password changed successfully. Please log in with your new password.</p>';
}
?>          

