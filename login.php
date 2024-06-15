<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;

    if ($username && $email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                $token = bin2hex(random_bytes(32));
                $_SESSION['token'] = $token;
                $_SESSION['role'] = $user['role'];

                $expires = time() + 3600;
                $datetime = date('Y-m-d H:i:s', $expires);
                setcookie('session_token', $token, [
                    'expires' => $expires,
                    // 'path' => '/',
                    // 'domain' => 'baastheowebsite.000webhostapp.com/', // Change to your domain
                    // 'secure' => false, // Ensure HTTPS
                    // 'httponly' => false, // Accessible only by the server
                    // 'samesite' => 'Lax' // CSRF protection
                ]);

                $stmt = $conn->prepare("SELECT * FROM sessions WHERE user_id = ?");
                $stmt->bind_param("s", $_SESSION['user']['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $session = $result->fetch_assoc();
                    $stmt = $conn->prepare("UPDATE sessions SET session_token=?, expires_at=? WHERE user_id=?");
                    $stmt->bind_param("sss", $token, $datetime, $_SESSION['user']['user_id']);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("INSERT INTO sessions (user_id, session_token, expires_at) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $_SESSION['user']['user_id'], $token, $datetime);
                    $stmt->execute();
                }



                switch ($_SESSION['role']) {
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'merchant':
                        header("Location: merchant_dashboard.php");
                        break;
                    case 'customer':
                        header("Location: shop.php");
                        break;
                    default:
                        $message = 'Error!';
                }
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "No user found with that username or email.";
        }
    } else {
        $message = "Please provide username, email, and password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            max-width: 400px;
            width: 100%;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        label {
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            text-align: left;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }

        input[type="submit"] {
            padding: 0.75rem;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 1rem;
            font-weight: 700;
            color: #dc3545;
        }

        img {
            margin-bottom: 1.5rem;
            border-radius: 50%;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <img src="/Images/Logo_1.png" alt="Website Logo">
        <?php if (isset($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>