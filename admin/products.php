<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F0F5E6; /* Light green-yellow, like a field */
            margin: 0;
            padding: 0;
            color: #333;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px;
        }
        h2 {
            color: #2F4F2F; /* Dark green, like leaves */
            margin-bottom: 20px;
        }
        .add-product-btn {
            display: inline-block;
            background-color: #556B2F; /* Olive green */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        .add-product-btn:hover {
            background-color: #2F4F2F; /* Dark green */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #FFFFFF;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #C0D6BA; /* Soft green-gray */
            color: #2F4F2F;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #E0E7D2; /* Lighter green on hover */
        }
        td a {
            color: #556B2F; /* Olive green */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        td a:hover {
            color: #2F4F2F; /* Darker green on hover */
        }
        td img {
            max-width: 50px;
            height: auto;
            border-radius: 3px;
        }
        .action-icon {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>All Products</h2>
        <a href="add_product.php" class="add-product-btn">Add New Product</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM products");
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td>Tsh.<?= number_format($row['price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['stock']) ?></td>
                           <td>
  <img src="<?= htmlspecialchars('uploads/' . basename($row['image'])) ?>" alt="Product Image">
</td>

                            <td>
                                <a href="edit_product.php?id=<?= htmlspecialchars($row['id']) ?>">
                                    <i class="fas fa-edit action-icon"></i>Edit
                                </a> |
                                <a href="delete_product.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash-alt action-icon" style="color:red"></i>Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile;
                else: ?>
                    <tr>
                        <td colspan="7">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>