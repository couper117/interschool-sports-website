<?php
include '../config.php';
include '../auth.php';
require_login();

$message = '';
$user_id = $_SESSION['user_id']; // From your session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = '<p style="color:red">All fields are required.</p>';
    } elseif ($new_password !== $confirm_password) {
        $message = '<p style="color:red">New password and confirmation do not match.</p>';
    } elseif (strlen($new_password) < 8) {
        $message = '<p style="color:red">New password must be at least 8 characters long.</p>';
    } else {
        // Fetch current hashed password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (!password_verify($current_password, $hashed_password)) {
            $message = '<p style="color:red">Current password is incorrect.</p>';
        } else {
            // Hash new password
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Update in DB
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_hashed, $user_id);

            if ($update_stmt->execute()) {
                $message = '<p style="color:green">Password changed successfully! Please log in again with your new password.</p>';
                // Optional: force logout after change
                // session_destroy();
                // header("Location: ../admin/login.php?msg=password_changed");
                // exit;
            } else {
                $message = '<p style="color:red">Error updating password: ' . $conn->error . '</p>';
            }
            $update_stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container" style="max-width:500px; margin:60px auto;">
        <h2>Change Your Password</h2>

        <?php if ($message): ?>
            <div style="padding:12px; margin:20px 0; border-radius:8px; <?= strpos($message, 'success') !== false ? 'background:#e8f5e9;color:#2e7d32;' : 'background:#ffebee;color:#c62828;' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label for="current_password">Current Password:</label><br>
            <input type="password" id="current_password" name="current_password" required minlength="8"><br><br>

            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password" required minlength="8"><br><br>

            <label for="confirm_password">Confirm New Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8"><br><br>

            <button type="submit" class="btn" style="width:100%; padding:14px; font-size:1.1rem;">
                Update Password
            </button>
        </form>

        <p style="margin-top:30px; text-align:center;">
            <a href="dashboard.php">← Back to Dashboard</a>
        </p>
    </div>
</body>
</html>