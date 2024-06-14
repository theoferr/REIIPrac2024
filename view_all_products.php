<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!check_session() || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize search term
$search_term = "";
if (isset($_GET['search_term'])) {
    $search_term = "%" . $_GET['search_term'] . "%";
} else {
    $search_term = "%";
}

// Fetch all products based on search term
$products = [];
$productStmt = $conn->prepare("
    SELECT p.product_id, p.name, p.description, p.price, p.stock, p.image_url, 
           p.merchant_id, m.username AS merchant_name, m.email AS merchant_email
    FROM products p
    JOIN users m ON p.merchant_id = m.user_id
    WHERE p.name LIKE ?
");
$productStmt->bind_param("s", $search_term);
$productStmt->execute();
$productResult = $productStmt->get_result();
while ($productRow = $productResult->fetch_assoc()) {
    $products[] = $productRow;
}
$productStmt->close();

function refundCustomer($conn, $product_id)
{
    // Fetch all orders containing the product
    $orderStmt = $conn->prepare("
        SELECT o.user_id, oi.quantity, oi.price 
        FROM order_items oi 
        JOIN orders o ON oi.order_id = o.order_id  
        WHERE oi.product_id = ?
    ");
    $orderStmt->bind_param("i", $product_id);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();

    while ($orderRow = $orderResult->fetch_assoc()) {
        $user_id = $orderRow['user_id'];
        $refund_amount = $orderRow['quantity'] * $orderRow['price'];

        // Update the user's wallet
        $walletStmt = $conn->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
        $walletStmt->bind_param("di", $refund_amount, $user_id);
        $walletStmt->execute();
        $walletStmt->close();
    }
    $orderStmt->close();
}

// Handle product removal and message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    $merchant_id = $_POST['merchant_id'];
    $admin_id = $_SESSION['user']['user_id'];
    $message = $_POST['message'];

    // Get the product name
    $productNameStmt = $conn->prepare("SELECT name FROM products WHERE product_id = ?");
    $productNameStmt->bind_param("i", $product_id);
    $productNameStmt->execute();
    $productNameStmt->bind_result($product_name);
    $productNameStmt->fetch();
    $productNameStmt->close();

    // Refund customers who bought the product
    refundCustomer($conn, $product_id);

    // Fetch and delete orders related to the product
    $orderStmt = $conn->prepare("
        SELECT DISTINCT o.order_id 
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE oi.product_id = ?
    ");
    $orderStmt->bind_param("i", $product_id);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();

    $orderIds = [];
    while ($orderRow = $orderResult->fetch_assoc()) {
        $orderIds[] = $orderRow['order_id'];
    }
    $orderStmt->close();

    // Delete orders from the orders table
    if (!empty($orderIds)) {
        $orderIdsStr = implode(',', $orderIds);
        $deleteOrdersStmt = $conn->prepare("DELETE FROM orders WHERE order_id IN ($orderIdsStr)");
        $deleteOrdersStmt->execute();
        $deleteOrdersStmt->close();
    }

    // Remove product from order_items table
    $orderItemStmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
    $orderItemStmt->bind_param("i", $product_id);
    $orderItemStmt->execute();
    $orderItemStmt->close();

    // Remove product from products table
    $removeStmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $removeStmt->bind_param("i", $product_id);
    $removeStmt->execute();
    $removeStmt->close();

    // Send message to merchant
    $messageStmt = $conn->prepare("INSERT INTO merchant_messages (product_id, product_name, merchant_id, admin_id, message) VALUES (?, ?, ?, ?, ?)");
    $messageStmt->bind_param("isiis", $product_id, $product_name, $merchant_id, $admin_id, $message);
    $messageStmt->execute();
    $messageStmt->close();

    // Refresh the page to update the product list
    header("Location: view_all_products.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Products</title>
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

        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        .product {
            margin-bottom: 2rem;
            text-align: left;
            border-bottom: 1px solid #ced4da;
            padding-bottom: 1rem;
        }

        .product img {
            max-width: 200px;
            max-height: 200px;
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
        input[type="number"],
        textarea {
            width: calc(100% - 20px);
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }

        input[type="submit"],
        button {
            padding: 0.75rem 2rem;
            font-weight: 700;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover,
        button:hover {
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
        <div class="top-bar">
            <h2>All Products</h2>
            <a href="admin.php" class="view-all-products"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
        </div>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search_term" placeholder="Search for a product" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
                <input type="submit" value="Search">
            </form>
        </div>
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: R<?php echo htmlspecialchars($product['price']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                    <p>Merchant: <?php echo htmlspecialchars($product['merchant_name']); ?> (<?php echo htmlspecialchars($product['merchant_email']); ?>)</p>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="merchant_id" value="<?php echo $product['merchant_id']; ?>">
                        <label for="message">Reason for Removal:</label>
                        <textarea id="message" name="message" rows="4" required></textarea>
                        <input type="submit" name="remove_product" value="Remove Product">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</body>

</html>