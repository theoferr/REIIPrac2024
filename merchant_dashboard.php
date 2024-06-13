<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'merchant') {
    header("Location: login.php");
    exit();
}

$merchant_id = $_SESSION['user']['user_id'];

// Handle message dismissal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dismiss_message'])) {
    $dismissed_message_id = $_POST['message_id'];
    if (!isset($_SESSION['dismissed_messages'])) {
        $_SESSION['dismissed_messages'] = [];
    }
    $_SESSION['dismissed_messages'][] = $dismissed_message_id;
}

// Fetch messages related to deleted products for the merchant
$messages = [];
$messageStmt = $conn->prepare("
    SELECT mm.message_id, mm.message, mm.product_name, mm.created_at
    FROM merchant_messages mm
    WHERE mm.merchant_id = ?
    ORDER BY mm.created_at DESC
");
$messageStmt->bind_param("i", $merchant_id);
$messageStmt->execute();
$messageResult = $messageStmt->get_result();

while ($messageRow = $messageResult->fetch_assoc()) {
    // Filter out dismissed messages
    if (!isset($_SESSION['dismissed_messages']) || !in_array($messageRow['message_id'], $_SESSION['dismissed_messages'])) {
        $messages[] = $messageRow;
    }
}
$messageStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
        a {
            display: inline-block;
            margin: 0.5rem 0;
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
        .messages {
            margin-top: 2rem;
            text-align: left;
        }
        .message-box {
            background-color: #f1f1f1;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .message-box h3 {
            margin: 0 0 0.5rem 0;
        }
        .message-box p {
            margin: 0;
        }
        .message-box .date {
            font-size: 0.8rem;
            color: #888;
        }
        .message-box form {
            margin-top: 0.5rem;
        }
        .message-box button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
        }
        .message-box button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <a href="add_product.php">Add Product</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="view_orders.php">View Orders</a>
        <a href="index.php?logout=true">Logout</a>

        <?php if (!empty($messages)): ?>
            <div class="messages">
                <h2>Deleted Items and Messages</h2>
                <?php foreach ($messages as $message): ?>
                    <div class="message-box">
                        <h3>Product: <?php echo htmlspecialchars($message['product_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="date">Deleted on: <?php echo htmlspecialchars($message['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <form method="POST" action="">
                            <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                            <button type="submit" name="dismiss_message">Dismiss</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
