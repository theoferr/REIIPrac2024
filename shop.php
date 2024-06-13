<?php
include 'practicalDB.php';
include 'session.php';
session_start();

if (!check_session() || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

// Fetch departments
$departments = [];
$deptStmt = $conn->prepare("SELECT department_id, name FROM departments ORDER BY name");
$deptStmt->execute();
$deptResult = $deptStmt->get_result();
while ($deptRow = $deptResult->fetch_assoc()) {
    $departments[] = $deptRow;
}
$deptStmt->close();

// Fetch products based on department
$products = [];
$department_filter = isset($_GET['department']) ? (int)$_GET['department'] : 0;
$search_term = isset($_GET['search_term']) ? "%" . $_GET['search_term'] . "%" : "%";

$query = "SELECT * FROM products WHERE stock > 0 AND name LIKE ?";
$params = ["s", $search_term];

if ($department_filter > 0) {
    $query .= " AND department_id = ?";
    $params[0] .= "i";
    $params[] = $department_filter;
}

$stmt = $conn->prepare($query);
$stmt->bind_param(...$params);
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
        /* Existing and new styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .sidebar {
            text-align: left;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 200px;
            width: 100%;
            margin: 2rem;
        }

        .sidebar img {
            margin-bottom: 1.5rem;
            max-width: 100%;
            height: auto;
        }

        .sidebar h3 {
            font-size: 1.2rem;
            color: #343a40;
            margin-bottom: 1rem;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 0.5rem;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: 700;
            display: block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
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

        .view-basket,
        .view-orders {
            text-decoration: none;
            color: #007bff;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .view-basket:hover,
        .view-orders:hover {
            color: #0056b3;
        }

        .view-basket i,
        .view-orders i {
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
    <div class="sidebar">
        <a href="shop.php"><img src="images/logo_1.png" alt="Website Logo"></a>
        <h3>Departments</h3>
        <ul>
            <li><a href="shop.php">All Departments</a></li>
            <?php foreach ($departments as $dept) : ?>
                <li><a href="?department=<?= $dept['department_id'] ?>"><?= htmlspecialchars($dept['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="container">
        <div class="top-bar">
            <h2>Shop</h2>
            <div>
                <a href="basket.php" class="view-basket"><i class="fas fa-shopping-basket"></i> View Basket</a>
                <a href="view_customer_orders.php" class="view-orders"><i class="fas fa-box"></i> View Orders</a>
            </div>
        </div>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search_term" placeholder="Search for a product">
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
                    <form method="POST" action="add_to_basket.php">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['stock']); ?>" required>
                        <input type="submit" value="Add to Basket">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No products found.</p>
        <?php endif; ?>
        <div class="links">
            <a href="index.php?logout=true">Logout</a>
            <a href="wallet.php">Manage Funds</a>
        </div>
    </div>
</body>

</html>