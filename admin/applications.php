<?php
// Start a new session to handle user login state and include necessary files
session_start();
include 'auth_check.php';
// Include the database configuration file. This is crucial for connecting to the DB.
require_once 'config.php';



// Function to safely get data from the applications table
function getApplications($conn) {
    // Prepare a SQL query to select all data from the 'applications' table
    // Ordering by the latest applications first
    $sql = "SELECT * FROM applications ORDER BY id DESC";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        // Log the error and return an empty array
        error_log("Error fetching applications: " . $conn->error);
        return [];
    }

    // Fetch all the results into an associative array
    $applications = $result->fetch_all(MYSQLI_ASSOC);

    // Free the result set
    $result->free();

    return $applications;
}

// Get the applications data from the database
$applications = getApplications($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Agronomy Farms</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Use a nature-inspired font from Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F4E1; /* Soft, earthy background */
        }
        
        .page-title {
            color: #1A5319; /* Deep forest green */
            border-bottom: 2px solid #6A994E; /* Grass green border */
        }
        
        /* Custom styling for the table headers */
        th {
            background-color: #6A994E;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 12px 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Custom styling for the table rows */
        tr:nth-child(even) {
            background-color: #F0F4E8; /* Light green for alternating rows */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!-- Main content area for the dashboard -->
    <div style="margin-left:220px; padding:20px;">
        <div class="page-content w-full max-w-7xl bg-white p-8 rounded-lg shadow-xl">
            <!-- Dashboard Header -->
            <h1 class="page-title text-4xl sm:text-5xl font-bold pb-4 mb-8 flex items-center gap-4">
                <i class="fas fa-chart-line text-[#6A994E]"></i> Admin Dashboard
            </h1>

            <!-- Subheading for the applications table -->
            <h2 class="text-3xl font-bold text-[#1A5319] mt-8 mb-6">Submitted Crop Applications</h2>
            
            <!-- Check if there are any applications to display -->
            <?php if (empty($applications)): ?>
                <p class="text-center text-gray-600 text-lg mt-12">No crop applications have been submitted yet.</p>
            <?php else: ?>
                <!-- Applications Table -->
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Application ID</th>
                                <th class="px-4 py-3">Farmer Name</th>
                                <th class="px-4 py-3">Phone Number</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Crop Name</th>
                                <th class="px-4 py-3">Quantity (kg)</th>
                                <th class="px-4 py-3">Location</th>
                                <th class="px-4 py-3">Submission Date</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through the applications data and display each row -->
                            <?php foreach ($applications as $application): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors">
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['id']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['full_name']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['phone_number']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['email']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['crop_name']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['quantity_kg']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['location']); ?></td>
                                    <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($application['submission_date']); ?></td>
                                    <td class="px-4 py-3">
                                        <!-- Example action buttons -->
                                        <button class="bg-green-500 text-white px-3 py-1 rounded-full text-sm hover:bg-green-600 transition-colors">View</button>
                                        <button class="bg-red-500 text-white px-3 py-1 rounded-full text-sm hover:bg-red-600 transition-colors">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>