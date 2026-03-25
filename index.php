<?php
require 'config.php';

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
)");

// Handle Add Product
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $conn->query("INSERT INTO products (name, price) VALUES ('$name', '$price')");
    header('Location: index.php');
    exit();
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header('Location: index.php');
    exit();
}

// Handle Update Product
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $conn->query("UPDATE products SET name='$name', price='$price' WHERE id=$id");
    header('Location: index.php');
    exit();
}

// Fetch products
$products = $conn->query("SELECT * FROM products");

// If editing, fetch product data
$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $product = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Product CRUD</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        form { margin-bottom: 20px; }
        input {padding: 10px; font-size: 16px;}
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <form method="post">
        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="text" name="name" value="<?= $product['name'] ?>" required>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
            <button type="submit" name="update">Update</button>
            <a href="index.php">Cancel</a>
        <?php else: ?>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <button type="submit" name="add">Add Product</button>
        <?php endif; ?>
    </form>
    <table>
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
        <?php while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['price'] ?></td>
                <td class="actions">
                    <a href="?edit=<?= $row['id'] ?>">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>