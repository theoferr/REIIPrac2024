<?php
// session.php - Session management
session_start();

function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_destroy();
}

function checkSession() {
    return isset($_SESSION['user']);
}
?>