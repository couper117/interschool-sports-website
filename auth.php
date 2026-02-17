<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_pass);
    if ($stmt->fetch() && password_verify($password, $hashed_pass)) {
        $_SESSION['user_id'] = $id;
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: ../index.php');
}
?>