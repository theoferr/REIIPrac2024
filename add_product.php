<?php
include 'practicalDB.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'merchant') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $merchant_id = $_SESSION['user']['user_id'];
    $image_url = $_POST["image_url"];

    $stmt = $conn->prepare("INSERT INTO products (merchant_id, name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdis", $merchant_id, $name, $description, $price, $stock, $image_url);

    if ($stmt->execute()) {
        $message = "Product added successfully!";
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
    <title>Add Product</title>
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
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }
        label {
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            text-align: left;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            color: #495057;
        }
        input[type="submit"] {
            padding: 0.75rem;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>
            
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url">
            
            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
