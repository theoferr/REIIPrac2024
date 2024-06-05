<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];
$products = [];
$total = 0.0;

if ($basket) {
    $product_ids = implode(',', array_keys($basket));
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($product_ids)");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $total += $row['price'] * $basket[$row['product_id']];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Basket</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            position: relative;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        .product {
            margin-bottom: 1.5rem;
        }
        .product h3 {
            margin: 0.5rem 0;
            color: #343a40;
        }
        .product p {
            margin: 0.25rem 0;
            color: #495057;
        }
        .total {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
            color: #007bff;
        }
        form {
            margin-bottom: 1rem;
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
            position: absolute;
            top: 1rem;
            right: 1rem;
            margin-right: 8rem; /* Adjusted for proper spacing */
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
        <h2>Your Basket</h2>
        <span class="total">Total: R<?php echo number_format($total, 2); ?></span>
        <?php if ($products): ?>
            <form method="POST" action="checkout.php">
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: R<?php echo htmlspecialchars($product['price']); ?></p>
                        <p>Quantity: <?php echo $basket[$product['product_id']]; ?></p>
                    </div>
                <?php endforeach; ?>
                <input type="submit" value="Checkout">
            </form>
        <?php else: ?>
            <p>Your basket is empty.</p>
        <?php endif; ?>
        <div class="links">
            <a href="shop.php">Continue Shopping</a>
            <a href="index.php">Logout</a>
        </div>
    </div>
</body>
</html>
