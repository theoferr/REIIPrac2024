<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'merchant') {
    header("Location: login.php");
    exit();
}

$merchant_id = $_SESSION['user']['user_id'];
$products = [];
$departments = [];

// Fetch departments
$deptStmt = $conn->prepare("SELECT department_id, name FROM departments");
$deptStmt->execute();
$deptResult = $deptStmt->get_result();
while ($deptRow = $deptResult->fetch_assoc()) {
    $departments[$deptRow['department_id']] = $deptRow['name'];
}
$deptStmt->close();

if (isset($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM products WHERE merchant_id = ? AND name LIKE ?");
    $stmt->bind_param("is", $merchant_id, $search);
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE merchant_id = ?");
    $stmt->bind_param("i", $merchant_id);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $image_url = $_POST["image_url"];
    $department_id = $_POST["department_id"];  // Capture the department ID from the form

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, department_id = ? WHERE product_id = ? AND merchant_id = ?");
    $stmt->bind_param("ssdisiii", $name, $description, $price, $stock, $image_url, $department_id, $product_id, $merchant_id);

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        .message {
            margin-top: 1rem;
            font-weight: 700;
            color: #28a745;
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
        <h2>Manage Products</h2>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by product name">
                <input type="submit" value="Search">
            </form>
        </div>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    
                    <label for="department_id">Department:</label>
                    <select id="department_id" name="department_id">
                        <?php foreach ($departments as $id => $name): ?>
                            <option value="<?= $id ?>" <?php if ($id == $product['department_id']) echo 'selected'; ?>><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                    
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
                    
                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>
                    
                    <input type="submit" value="Update Product">
                </form>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
        <a href="merchant_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
