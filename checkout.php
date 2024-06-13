<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT balance FROM wallets WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $wallet = $result->fetch_assoc();

    if ($wallet['balance'] >= $total) {
        $new_balance = $wallet['balance'] - $total;

        $conn->begin_transaction();

        try {
            // Update the user's wallet balance
            $stmt = $conn->prepare("UPDATE wallets SET balance = ? WHERE user_id = ?");
            $stmt->bind_param("di", $new_balance, $user_id);
            $stmt->execute();

            // Create a new order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
            $stmt->bind_param("id", $user_id, $total);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // Add items to the order and reduce the stock
            foreach ($products as $product) {
                $quantity = $basket[$product['product_id']];
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $order_id, $product['product_id'], $quantity, $product['price']);
                $stmt->execute();

                $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
                $stmt->bind_param("ii", $quantity, $product['product_id']);
                $stmt->execute();
            }

            $conn->commit();
            unset($_SESSION['basket']);
            echo "Checkout successful!";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Checkout failed: " . $e->getMessage();
        }

        $stmt->close();
    } else {
        echo "Insufficient balance.";
    }

    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            max-width: 600px;
            width: 100%;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        p {
            font-size: 1rem;
            color: #495057;
            margin-bottom: 1rem;
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
        <h2>Checkout</h2>
        <?php if ($products): ?>
            <p>Total: R<?php echo $total; ?></p>
            <form method="POST" action="">
                <input type="submit" value="Confirm and Pay">
            </form>
        <?php else: ?>
            <p>Your basket is empty.</p>
        <?php endif; ?>
        <div class="links">
            <a href="basket.php">Back to Basket</a>
            <a href="index.php?logout=true">Logout</a>
        </div>
    </div>
</body>
</html>
