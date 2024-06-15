<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!check_session() || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Fetch user's orders, including merchant details for contact
$orders = [];
$stmt = $conn->prepare("
    SELECT o.*, p.merchant_id, m.email AS merchant_email 
    FROM orders o 
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN users m ON p.merchant_id = m.user_id
    WHERE o.user_id = ? 
    AND o.status IN ('pending', 'shipped')
    ORDER BY FIELD(o.status, 'pending', 'shipped'), o.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Process status update to "delivered"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_delivered'])) {
    $order_id = $_POST['order_id'];
    $merchant_id = $_POST['merchant_id'];
    $total = $_POST['total'];

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        // Add funds to merchant's wallet
        $stmt = $conn->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $total, $merchant_id);
        if ($stmt->execute()) {
            echo "Order marked as delivered and funds transferred to merchant.";
        } else {
            echo "Error transferring funds: " . $stmt->error;
        }
    } else {
        echo "Error updating order status: " . $stmt->error;
    }
    $stmt->close();
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
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
            max-width: 800px;
            width: 100%;
            margin: 2rem auto;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        .order {
            margin-bottom: 2rem;
            text-align: left;
            border-bottom: 1px solid #ced4da;
            padding-bottom: 1rem;
            position: relative;
            /* Position relative for absolute positioning of the button */
        }

        .status-pending {
            color: #dc3545;
        }

        .status-shipped {
            color: #ffc107;
        }

        .status-delivered {
            color: #28a745;
        }

        .merchant-contact {
            margin-top: 1rem;
            font-style: italic;
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

        form {
            position: absolute;
            /* Absolute positioning */
            right: 0;
            /* Align to the right */
            top: 50%;
            /* Center vertically */
            transform: translateY(-50%);
            /* Adjust vertical alignment */
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Your Orders</h2>
        <?php foreach ($orders as $order) : ?>
            <div class="order">
                <p>Order ID: <?= htmlspecialchars($order['order_id']); ?></p>
                <p>Status: <span class="<?= 'status-' . strtolower($order['status']); ?>"><?= htmlspecialchars($order['status']); ?></span></p>
                <p>Total: R<?= htmlspecialchars($order['total']); ?></p>
                <p>Merchant Email: <?= htmlspecialchars($order['merchant_email']); ?></p>
                <?php if ($order['status'] == 'shipped') : ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                        <input type="hidden" name="merchant_id" value="<?= $order['merchant_id']; ?>">
                        <input type="hidden" name="total" value="<?= $order['total']; ?>">
                        <input type="submit" name="mark_delivered" value="Mark as Delivered">
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (empty($orders)) : ?>
            <p>No orders found.</p>
        <?php endif; ?>
        <div class="links">
            <a href="shop.php">Continue Shopping</a>
            <a href="index.php?logout=true">Logout</a>
        </div>
    </div>
</body>

</html>