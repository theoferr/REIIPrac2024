<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!check_session() || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Fetch user's orders, joined with products and merchants to get the merchant's email for pending orders
$orders = [];
$stmt = $conn->prepare("
    SELECT o.*, p.merchant_id, m.email AS merchant_email 
    FROM orders o 
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.product_id
    LEFT JOIN users m ON p.merchant_id = m.user_id
    WHERE o.user_id = ? 
    GROUP BY o.order_id 
    ORDER BY FIELD(o.status, 'pending', 'shipped', 'delivered'), o.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
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
                <p>Date: <?= htmlspecialchars(date('Y-m-d H:i', strtotime($order['created_at']))); ?></p>
                <?php if ($order['status'] == 'pending') : ?>
                    <p class="merchant-contact">Contact Merchant: <?= htmlspecialchars($order['merchant_email']); ?></p>
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