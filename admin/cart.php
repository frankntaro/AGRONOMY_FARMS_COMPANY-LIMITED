<?php
session_start();
require_once 'config.php';
include 'includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$cartTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.min.css">
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        /* General body and typography */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        /* Cart container styling */
        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .empty-cart {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            padding: 20px;
        }
        .empty-cart a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }
        
        /* Table styling for cart items */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Roboto', sans-serif;
        }
        .cart-table thead tr {
            background-color: green;
            color: white;
        }
        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid black;
        }
        .cart-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .product-info {
            display: flex;
            align-items: center;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .cart-table tfoot tr {
            font-size: 1.1em;
            background-color: #f0f0f0;
        }

        /* Checkout button styling */
        .checkout-button-container {
            text-align: right;
            margin-top: 20px;
        }
        .checkout-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }
        .checkout-button:hover {
            background-color: #0056b3;
        }

        /* Responsive Styling for Mobile */
        @media (max-width: 768px) {
            .cart-container {
                margin: 20px;
                padding: 15px;
            }
            .cart-table th,
            .cart-table td {
                padding: 10px;
                font-size: 0.9em;
            }
            .product-image {
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }
            .checkout-button-container {
                text-align: center;
            }
            .checkout-button {
                display: block;
                width: 100%;
                margin-top: 15px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart)): ?>
        <p class="empty-cart">Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // --- SECURE SQL INJECTION FIX ---
                    // Dynamically create placeholders for the IN clause
                    $productIds = array_keys($cart);
                    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
                    $types = str_repeat('i', count($productIds));

                    $sql = "SELECT id, name, price, image FROM products WHERE id IN ($placeholders)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param($types, ...$productIds);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $productsInCart = [];
                        while ($row = $result->fetch_assoc()) {
                            $productsInCart[$row['id']] = $row;
                        }
                        $stmt->close();
                    }

                    foreach ($cart as $productId => $quantity):
                        if (isset($productsInCart[$productId])):
                            $product = $productsInCart[$productId];
                            $subtotal = $product['price'] * $quantity;
                            $cartTotal += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                        <span><?= htmlspecialchars($product['name']) ?></span>
                                    </div>
                                </td>
                                <td>TSh <?= number_format($product['price'], 2) ?></td>
                                <td><?= htmlspecialchars($quantity) ?></td>
                                <td>TSh <?= number_format($subtotal, 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                        <td><strong>TSh <?= number_format($cartTotal, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="checkout-button-container">
            <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>


</body>
</html>