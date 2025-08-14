<?php
session_start();
require_once 'config.php';
include 'includes/header.php';

// Check if the user is logged in and an order ID is provided
if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    echo "<div class='container main-content' style='text-align:center; padding: 2rem;'>Invalid request. Please log in to view your orders.</div>";
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = intval($_SESSION['user_id']);

// Use a prepared statement to fetch order details securely
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<div class='container main-content' style='text-align:center; padding: 2rem;'>Order not found or you don't have permission to view it.</div>";
    exit;
}
?>
<link rel="stylesheet" href="includes/assets/css/style.css">
<style>
    /* Styles for the order details page */
    .order-details-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: var(--card-bg);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .order-header {
        border-bottom: 2px solid var(--light-bg);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .order-header h2 {
        font-family: 'Montserrat', sans-serif;
        color: var(--primary-green);
        font-weight: 700;
        font-size: 1.8rem;
    }
    
    .order-summary p {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .order-summary p strong {
        color: var(--dark-green);
    }
    
    .items-header {
        font-family: 'Montserrat', sans-serif;
        color: var(--dark-green);
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--light-green);
        padding-bottom: 0.5rem;
    }
    
    /* Responsive table styling */
    .table-responsive {
        overflow-x: auto;
    }
    
    .order-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        margin-top: 1rem;
        min-width: 500px; /* Ensure table is wide enough to scroll on mobile */
    }
    
    .order-table th, .order-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }
    
    .order-table thead {
        background-color: var(--primary-green);
        color: white;
    }
    
    .order-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    
    .order-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    @media (max-width: 768px) {
        .order-details-container {
            margin: 1rem;
            padding: 1rem;
        }
        
        .order-header h2 {
            font-size: 1.5rem;
        }
        
        .order-summary p {
            font-size: 0.9rem;
        }
    }
</style>

<div class="container main-content">
    <div class="order-details-container">
        <div class="order-header">
            <h2>Order #<?= htmlspecialchars($order['id']) ?> Details</h2>
        </div>
        
        <div class="order-summary">
            <p><strong>Order Status:</strong> <span class="badge status-<?= strtolower(htmlspecialchars($order['status'])) ?>"><?= htmlspecialchars($order['status']) ?></span></p>
            <p><strong>Total Amount:</strong> TSh <?= number_format($order['total_amount']) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
        </div>
        
        <h3 class="items-header">Items in this Order</h3>
        
        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price at Purchase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Securely fetch and display order items
                    $stmt_items = $conn->prepare("
                        SELECT oi.quantity, oi.price, p.name 
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?
                    ");
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();

                    while ($item = $result_items->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>TSh <?= number_format($item['price']) ?></td>
                        </tr>
                    <?php endwhile;
                    $stmt_items->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>