<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user'])) {
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
    $wallet = $result->fetch_assoc();
    $stmt->close();
    return $wallet ? $wallet['balance'] : 0;
}

$current_balance = getCurrentBalance($conn, $user_id);

// Handle form submission to add funds
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    if ($amount > 0) {
        // Check if wallet exists
        $stmt = $conn->prepare("SELECT wallet_id FROM wallets WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing wallet balance
            $stmt = $conn->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
            $stmt->bind_param("di", $amount, $user_id);
        } else {
            // Create new wallet entry
            $stmt = $conn->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, ?)");
            $stmt->bind_param("id", $user_id, $amount);
        }

        if ($stmt->execute()) {
            $current_balance = getCurrentBalance($conn, $user_id); // Update the current balance
            echo "Funds added successfully.";
        } else {
            echo "Error adding funds: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Funds</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 2rem auto;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        label {
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            text-align: left;
            display: block;
        }

        input[type="number"] {
            width: calc(100% - 20px);
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }

        input[type="submit"] {
            padding: 0.75rem 2rem;
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

        .links {
            margin-top: 2rem;
        }

        .links a {
            display: inline-block;
            margin-right: 1rem;
            color: #007bff;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Funds</h2>
        <p>Current Balance: R<span id="current_balance"><?php echo htmlspecialchars($current_balance, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <form method="POST" action="">
            <label for="amount">Add Funds:</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" required>
            <input type="submit" value="Add Funds">
        </form>
        <div class="links">
            <a href="shop.php">Back to Orders</a>
            <a href="index.php?logout=true">Logout</a>
        </div>
    </div>

    <script>
        // JavaScript to update balance dynamically after form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('current_balance').innerText = <?php echo json_encode($current_balance); ?>;
                    alert('Funds added successfully.');
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>