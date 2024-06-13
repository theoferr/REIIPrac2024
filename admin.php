<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!check_session() || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Change user roles and emails
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $new_email = $_POST['email'];
    
    $stmt = $conn->prepare("UPDATE users SET role = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $new_role, $new_email, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch users
$users = [];
$userStmt = $conn->prepare("SELECT user_id, username, email, role FROM users");
$userStmt->execute();
$userResult = $userStmt->get_result();
while ($userRow = $userResult->fetch_assoc()) {
    $users[] = $userRow;
}
$userStmt->close();

// Fetch sales data for the week
$sales = [];
$topMerchant = null;
$salesStmt = $conn->prepare("
    SELECT p.merchant_id, m.username AS merchant_name, SUM(oi.quantity * oi.price) AS total_sales
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN users m ON p.merchant_id = m.user_id
    WHERE o.status = 'delivered' AND o.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
    GROUP BY p.merchant_id
    ORDER BY total_sales DESC
");
$salesStmt->execute();
$salesResult = $salesStmt->get_result();
while ($salesRow = $salesResult->fetch_assoc()) {
    $sales[] = $salesRow;
    if ($topMerchant === null) {
        $topMerchant = $salesRow;
    }
}
$salesStmt->close();

// Fetch unshipped products
$unshipped = [];
$unshippedStmt = $conn->prepare("
    SELECT o.order_id, p.name AS product_name, o.created_at, 
           b.username AS buyer_name, b.email AS buyer_email, 
           m.username AS merchant_name, m.email AS merchant_email
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN users b ON o.user_id = b.user_id
    JOIN users m ON p.merchant_id = m.user_id
    WHERE o.status = 'pending'
");
$unshippedStmt->execute();
$unshippedResult = $unshippedStmt->get_result();
while ($unshippedRow = $unshippedResult->fetch_assoc()) {
    $unshipped[] = $unshippedRow;
}
$unshippedStmt->close();

// Handle custom SQL query
$customQueryResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_query'])) {
    $query = $_POST['query'];
    if (!empty($query)) {
        $customQueryResult = $conn->query($query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            margin: 2rem;
        }
        h2, h3 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        table, th, td {
            border: 1px solid #ced4da;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
        }
        label {
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            text-align: left;
            display: block;
        }
        input[type="text"],
        select, textarea {
            width: calc(100% - 20px);
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }
        input[type="submit"], button {
            padding: 0.75rem 2rem;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #0056b3;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .view-all-products {
            text-decoration: none;
            color: #007bff;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .view-all-products:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h2>Admin Dashboard</h2>
            <a href="view_all_products.php" class="view-all-products"><i class="fas fa-boxes"></i> View All Products</a>
        </div>
        
        <!-- Change User Roles and Emails -->
        <h3>Change User Roles and Emails</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Change Role and Email</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            <select name="role">
                                <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                                <option value="merchant" <?= $user['role'] == 'merchant' ? 'selected' : '' ?>>Merchant</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <input type="submit" name="update_user" value="Update">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Weekly Sales and Top Merchant -->
        <h3>Weekly Sales</h3>
        <table>
            <tr>
                <th>Merchant</th>
                <th>Total Sales</th>
            </tr>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= htmlspecialchars($sale['merchant_name']) ?></td>
                    <td>R<?= number_format($sale['total_sales'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if ($topMerchant): ?>
            <p>Top Merchant of the Week: <?= htmlspecialchars($topMerchant['merchant_name']) ?> with R<?= number_format($topMerchant['total_sales'], 2) ?> in sales</p>
        <?php endif; ?>

        <!-- Unshipped Products -->
        <h3>Unshipped Products</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Order Date</th>
                <th>Buyer</th>
                <th>Buyer Email</th>
                <th>Merchant</th>
                <th>Merchant Email</th>
            </tr>
            <?php foreach ($unshipped as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['order_id']) ?></td>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= htmlspecialchars($item['created_at']) ?></td>
                    <td><?= htmlspecialchars($item['buyer_name']) ?></td>
                    <td><?= htmlspecialchars($item['buyer_email']) ?></td>
                    <td><?= htmlspecialchars($item['merchant_name']) ?></td>
                    <td><?= htmlspecialchars($item['merchant_email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Custom Query -->
        <h3>Custom SQL Query</h3>
        <form method="POST" action="">
            <textarea name="query" rows="4" cols="50" placeholder="Enter your SQL query here" required></textarea><br>
            <input type="submit" name="custom_query" value="Run Query">
        </form>
        <?php if ($customQueryResult): ?>
            <h4>Query Result</h4>
            <table>
                <?php
                if ($customQueryResult->num_rows > 0) {
                    echo '<tr>';
                    while ($field = $customQueryResult->fetch_field()) {
                        echo '<th>' . htmlspecialchars($field->name) . '</th>';
                    }
                    echo '</tr>';
                    while ($row = $customQueryResult->fetch_assoc()) {
                        echo '<tr>';
                        foreach ($row as $cell) {
                            echo '<td>' . htmlspecialchars($cell) . '</td>';
                        }
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td>No results found.</td></tr>';
                }
                ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
