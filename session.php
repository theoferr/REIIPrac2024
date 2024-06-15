<?php
include 'practicalDB.php';
error_reporting(0);
function logout()
{
    session_start();
    session_destroy();
    setcookie('session_token', '', time() - 3600, '/');
}

function check_session()
{
    global $conn;
    //echo '$_SESSION: '.json_encode($_SESSION).'<br>';
    //echo '$_COOKIE: '.json_encode($_COOKIE).'<br>';
    // Check if session and cookie are set
    if (!isset($_COOKIE['session_token'])) {
        return false;
    }

    // Retrieve user data from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user']['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        //echo '$user: '.json_encode($user) .'<br>';
        // Retrieve session data from database
        $stmt = $conn->prepare("SELECT * FROM sessions WHERE user_id = ?");
        $stmt->bind_param("s", $user['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $session = $result->fetch_assoc();
            //echo '$session: '.json_encode($session).'<br>';
            //echo '$_COOKIE: '.json_encode($_COOKIE).'<br>';
            // Check if the session token matches
            if ($_COOKIE['session_token'] === $session['session_token']) {
                // Check if the session is expired
                $current_datetime = new DateTime();
                $expiry_datetime = new DateTime($session['expires_at']);

                if ($current_datetime < $expiry_datetime) {
                    return true;
                }
            }
        }
    }

    return false;
}
