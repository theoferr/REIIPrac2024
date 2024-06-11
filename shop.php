<?php
include 'practicalDB.php';
include 'session.php';
session_start();

// Check if the token from the cookie matches the session token
if (!check_session() || $_SESSION !== 'customer') {
    header("Location: login.php");
    exit();
} 

$products = [];
$search_term = "";

if (isset($_GET['search_term'])) {
    $search_term = "%" . $_GET['search_term'] . "%";
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? AND stock > 0");
    $stmt->bind_param("s", $search_term);
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE stock > 0");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
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
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .view-basket {
            text-decoration: none;
            color: #007bff;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .view-basket:hover {
            color: #0056b3;
        }
        .view-basket i {
            margin-right: 0.5rem;
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
        <div class="top-bar">
            <h2>Shop</h2>
            <a href="basket.php" class="view-basket"><i class="fas fa-shopping-basket"></i> View Basket</a>
        </div>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search_term" placeholder="Search for a product">
                <input type="submit" value="Search">
            </form>
        </div>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: R<?php echo htmlspecialchars($product['price']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                    <form method="POST" action="add_to_basket.php">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['stock']); ?>" required>
                        <input type="submit" value="Add to Basket">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
        <div class="links">
            <a href="index.php?logout=true">Logout</a>
            <a href="wallet.php">Manage Funds</a>
        </div>
    </div>
</body>
</html>
