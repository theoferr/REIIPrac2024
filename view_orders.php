<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'merchant') {
    header("Location: login.php");
    exit();
}

$merchant_id = $_SESSION['user']['user_id'];

// Process form submission to update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch orders for the merchant
$orders = [];
if (isset($_GET['search_order_id'])) {
    $search_order_id = $_GET['search_order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND order_id IN (SELECT order_id FROM order_items WHERE product_id IN (SELECT product_id FROM products WHERE merchant_id = ?))");
    $stmt->bind_param("ii", $search_order_id, $merchant_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id IN (SELECT order_id FROM order_items WHERE product_id IN (SELECT product_id FROM products WHERE merchant_id = ?))");
    $stmt->bind_param("i", $merchant_id);
}

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
            max-width: 600px;
            width: 100%;
            margin: 2rem auto;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        form {
            margin-bottom: 1rem;
        }
        label {
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            text-align: left;
            display: block;
        }
        input[type="text"],
        select {
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
        hr {
            margin: 2rem 0;
            border: none;
            border-top: 1px solid #ced4da;
        }
        .search-bar {
            margin-bottom: 2rem;
        }
        .search-bar input[type="text"] {
            width: calc(100% - 22px);
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }
        .search-bar input[type="submit"] {
            padding: 0.75rem 2rem;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Orders</h2>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search_order_id" placeholder="Search by Order ID">
                <input type="submit" value="Search">
            </form>
        </div>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div>
                    <p>Order ID: <?php echo htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>User ID: <?php echo htmlspecialchars($order['user_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Total: <?php echo htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Status: <?php echo htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <label for="status">Update Status:</label>
                        <select id="status" name="status">
                            <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="shipped" <?php if ($order['status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                        </select><br><br>
                        <input type="submit" value="Update Status">
                    </form>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
        <a href="merchant_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
