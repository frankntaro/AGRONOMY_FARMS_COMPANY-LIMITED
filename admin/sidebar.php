<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="sidebar">
    <h3>Navigation</h3>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <!-- Changed the href to correctly link to the admin_dashboard.php file -->
        <li><a href="applications.php"><i class="fas fa-file-signature"></i> Applications</a></li>
        <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
        <li><a href="sales_report.php"><i class="fas fa-chart-line"></i> Reports</a></li>
        <li><a href="trends.php"><i class="fas fa-chart-area"></i> Trends</a></li>
        <li><a href="includes/login1.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>


<style>
    .sidebar {
        width: 200px;
        float: left;
        background-color: rgba(166, 192, 36, 1); /* A soft, light off-white color */
        height: 100vh;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .sidebar h3 {
        color: white; /* Sea green, representing plants and growth */
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 1em;
        margin-bottom: 20px;
        border-bottom: 2px solid #556B2F; /* Olive green, representing soil */
        padding-bottom: 10px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li {
        margin-bottom: 10px;
    }

    .sidebar a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: white; /* Olive green */
        font-weight: 500;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #E0E7D2; /* A lighter, subtle green for hover effect */
        color: #2E8B57; /* Darker green on hover */
    }

    .sidebar a.active {
        background-color: #2E8B57; /* Sea green for the active page */
        color: white;
    }
</style>