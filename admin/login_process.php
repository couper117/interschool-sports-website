<?php
include '../config.php';
include '../auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "<p style='color:red'>Invalid credentials</p>";
        include 'login.php';
    }
}
?>