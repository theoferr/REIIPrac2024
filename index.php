<?php
include 'session.php';
if (isset($_GET['logout'])) {
    // session_destroy();
    logout();
}

// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

// Additional headers which may be sent along with the CORS request
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

error_reporting(0);

// TTL (Time To Live) of the cookie stored in the browser.
ini_set('session.cookie_lifetime', 432000); // 5 days

// On the server side, the garbage collector should delete
// old sessions too, after the same TTL.
ini_set('session.gc_maxlifetime', 432000); // 5 days

// Fire the garbage collector only every 100 requests.
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Application</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        img {
            margin-bottom: 1.5rem;
            border-radius: 50%;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 25%;
        }

        a {
            display: inline-block;
            margin: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        a:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Welcome to Baas Theo's Website</h2>
        <img src="/Images/Logo_1.png" alt="Website Logo">
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
    </div>
</body>

</html>