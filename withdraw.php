<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'merchant' || !check_session()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Function to fetch current balance
function getCurrentBalance($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT balance FROM wallets WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $wallet = $result->fetch_assoc();
        return $wallet['balance'];
    }
    return 0; // Return 0 if no wallet is found
}

$current_balance = getCurrentBalance($conn, $user_id);

// Process withdrawal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['withdraw_amount'])) {
    $withdraw_amount = $_POST['withdraw_amount'];
    if ($withdraw_amount > 0 && $withdraw_amount <= $current_balance) {
        // Update wallet balance
        $stmt = $conn->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ?");
        $stmt->bind_param("di", $withdraw_amount, $user_id);
        if ($stmt->execute()) {
            $current_balance -= $withdraw_amount;  // Update current balance locally
            $message = "Withdrawal successful.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Invalid withdrawal amount.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Funds</title>
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

        input[type="number"],
        input[type="submit"] {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }

        input[type="submit"] {
            color: #fff;
            background-color: #007bff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            color: #dc3545;
            margin-top: 1rem;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Withdraw Funds</h2>
        <?php if (isset($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <p>Current Balance: R<?php echo number_format($current_balance, 2); ?></p>
        <form method="POST" action="">
            <label for="withdraw_amount">Amount to Withdraw:</label>
            <input type="number" id="withdraw_amount" name="withdraw_amount" step="0.01" min="0.01" max="<?php echo $current_balance; ?>" required>
            <input type="submit" value="Withdraw">
        </form>
        <a href="merchant_dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>