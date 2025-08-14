<?php
session_start();
require_once 'config.php';
include 'includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'checkout.php';
    header("Location: login.php");
    exit;
}

// Check if the cart is not empty
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

$cartTotal = 0;
$productDetails = [];

// Prepare the list of product IDs from the cart
$productIds = array_keys($cart);

// --- SECURE SQL INJECTION FIX ---
// Dynamically create placeholders for the IN clause
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$types = str_repeat('i', count($productIds));

// Securely fetch product details from the database using a prepared statement
$sql = "SELECT id, name, price, image FROM products WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param($types, ...$productIds);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($product = $result->fetch_assoc()) {
        $productDetails[$product['id']] = $product;
    }
    $stmt->close();
}
// ---------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        
        /* Centering and overall container styling */
        .checkout-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            text-align: center;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* Order Summary Table Styling */
        .checkout-summary {
            margin-bottom: 30px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .checkout-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Roboto', sans-serif;
        }
        .checkout-summary table th,
        .checkout-summary table td {
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid black;
        }
        .checkout-summary table thead tr {
            background-color:green;
            color: white;
        }
        .checkout-summary table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .checkout-summary table tfoot tr {
            font-size: 1.1em;
            background-color: #f0f0f0;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .checkout-summary table td:first-child {
            display: flex;
            align-items: center;
        }

        /* Payment Options Styling */
        .payment-form {
            margin-top: 40px;
        }
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
        .payment-option {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border: 1px solid black;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background-color: white;
        }
        .payment-option:hover {
            background-color: #f0f8ff;
            border-color: #007bff;
        }
        .payment-option input[type="radio"] {
            display: none;
        }
        .payment-option input[type="radio"]:checked + .payment-logo {
            box-shadow: 0 0 0 3px #007bff;
        }
        .payment-option input[type="radio"]:checked ~ span {
            font-weight: bold;
            color: #007bff;
        }
        .payment-logo {
            width: 80px;
            height: 50px;
            object-fit: contain;
            border-radius: 6px;
            padding: 5px;
            background-color: #fff;
            transition: box-shadow 0.2s ease;
        }
        .payment-option span {
            font-weight: 500;
            font-size: 1.1em;
            color: black;
            transition: color 0.2s ease;
        }

        .btn-success {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            background-color: green;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .btn-success:hover {
            background-color: yellowgreen;
        }

        /* Responsive Styling for Mobile */
        @media (max-width: 768px) {
            .checkout-container {
                margin: 20px;
                padding: 15px;
            }
            .checkout-summary table {
                font-size: 0.9em;
            }
            .checkout-summary table th,
            .checkout-summary table td {
                padding: 10px;
            }
            .product-image {
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }
            .payment-option {
                flex-direction: row;
                justify-content: flex-start;
                padding: 15px;
                gap: 10px;
            }
            .payment-logo {
                width: 60px;
                height: 40px;
            }
            .btn-success {
                font-size: 1.1em;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>
    
    <div class="checkout-summary">
        <h3>Order Summary</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $productId => $quantity):
                        $product = $productDetails[$productId];
                        $subtotal = $product['price'] * $quantity;
                        $cartTotal += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                <span><?= htmlspecialchars($product['name']) ?></span>
                            </td>
                            <td>TSh <?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($quantity) ?></td>
                            <td>TSh <?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>TSh <?= number_format($cartTotal, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="payment-form">
        <h3>Payment Information</h3>
        <form action="process_payment.php" method="POST">
            <div class="form-group">
                <label>Select Payment Method:</label>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Mix by Yas" required>
                        <img src="includes/IMAGES/yas.png" alt="Mix by Yas" class="payment-logo">
                        <span>Mix by Yas</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="M-Pesa" required>
                        <img src="includes/IMAGES/M-pesa-logo.png" alt="M-Pesa" class="payment-logo">
                        <span>M-Pesa</span>
                    </label>
                     <label class="payment-option">
                        <input type="radio" name="payment_method" value="halopesa" required>
                        <img src="includes/IMAGES/halopesa.png" alt="halopesa" class="payment-logo">
                        <span>Halo-Pesa</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Airtel-Money" required>
                        <img src="includes/IMAGES/airtelmoney.jpeg" alt="Airtel-Money" class="payment-logo">
                        <span>Airtel-Money</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="NMB BANK" required>
                        <img src="includes/IMAGES/NMB.png" alt="Bank Transfer" class="payment-logo">
                        <span>NMB BANK</span>
                    </label>
                     <label class="payment-option">
                        <input type="radio" name="payment_method" value="CRDB BANK" required>
                        <img src="includes/IMAGES/CRDB.png" alt="Bank Transfer" class="payment-logo">
                        <span>CRDB BANK</span>
                    </label>
                     <label class="payment-option">
                        <input type="radio" name="payment_method" value="NBC BANK" required>
                        <img src="includes/IMAGES/NBC.jpg" alt="Bank Transfer" class="payment-logo">
                        <span>NBC BANK</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-success" style="margin-top: 20px;">
                <i class="fas fa-check-circle"></i> Complete Order
            </button>
        </form>
    </div>
</div>


</body>
</html>