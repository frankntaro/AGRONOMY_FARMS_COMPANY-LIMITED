<?php
include 'auth_check.php';
include 'includes/db.php';

// Initialize variables
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $description = trim($_POST['description']);
    $imagePath = '';

    // Validate required fields
    if (empty($name) || empty($category) || empty($description) || $price <= 0 || $stock <= 0) {
        $error = "Please fill all required fields with valid data!";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    $error = "Failed to create upload directory!";
                }
            }

            if (empty($error)) {
                // Generate a unique and secure filename
                $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $imageName = bin2hex(random_bytes(16)) . '.' . $fileExt; // More secure filename
                $targetPath = $uploadDir . $imageName;

                // Validate image type and size
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    $error = "Only JPG, PNG, GIF, and WEBP images are allowed!";
                } elseif ($_FILES['image']['size'] > $maxFileSize) {
                    $error = "Image size must be less than 2MB!";
                } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Verify the file is a valid image after moving it
                    if (getimagesize($targetPath) === false) {
                        $error = "Uploaded file is not a valid image. It has been deleted.";
                        unlink($targetPath); // Delete the invalid file
                    } else {
                        $imagePath = 'uploads/' . $imageName; // Relative path for web access
                    }
                } else {
                    $error = "Failed to move uploaded file. Check directory permissions! Error: " . $_FILES['image']['error'];
                }
            }
        } else {
            $error = "Product image is required and must be uploaded properly!";
        }

        // Insert into database if no errors
        if (empty($error)) {
            $conn->begin_transaction(); // Start transaction

            try {
                // Use a prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO products (name, category, description, price, stock, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssdis", $name, $category, $description, $price, $stock, $imagePath);

                if ($stmt->execute()) {
                    $conn->commit();
                    $success = "Product added successfully! Redirecting...";
                    header("Refresh: 2; URL=products.php");
                    exit; // Ensure no further code is executed
                } else {
                    throw new Exception("Database error: " . $stmt->error);
                }
            } catch (Exception $e) {
                $conn->rollback();
                // Log the specific database error for debugging
                error_log("Database error in add_product.php: " . $e->getMessage());

                // Delete uploaded image if database insert failed
                if (!empty($imagePath) && file_exists($targetPath)) {
                    unlink($targetPath);
                }
                $error = "An unexpected database error occurred. Please try again.";
            } finally {
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        .form-container { margin-left: 220px; padding: 20px; }
        input, select, textarea { width: 100%; max-width: 500px; padding: 8px; margin-bottom: 10px; }
        button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="form-container" style="text-align: center;">
    <h2>Add New Product</h2>

    <?php if (!empty($error)) { ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <?php if (!empty($success)) { ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data" style="text-align: center;">
        <label>Name:*</label><br>
        <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required><br>

        <label>Category:*</label><br>
        <select name="category" required>
            <option value="tools" <?php echo (isset($_POST['category']) && $_POST['category'] === 'tools') ? 'selected' : ''; ?>>Tools</option>
            <option value="equipments" <?php echo (isset($_POST['category']) && $_POST['category'] === 'equipments') ? 'selected' : ''; ?>>Equipments</option>
            <option value="fertilizers" <?php echo (isset($_POST['category']) && $_POST['category'] === 'fertilizers') ? 'selected' : ''; ?>>Fertilizers</option>
            <option value="pesticides" <?php echo (isset($_POST['category']) && $_POST['category'] === 'pesticides') ? 'selected' : ''; ?>>Pesticides</option>
            <option value="herbicides" <?php echo (isset($_POST['category']) && $_POST['category'] === 'herbicides') ? 'selected' : ''; ?>>Herbicides</option>
            <option value="insecticides" <?php echo (isset($_POST['category']) && $_POST['category'] === 'insecticides') ? 'selected' : ''; ?>>Insecticides</option>
            <option value="seeds" <?php echo (isset($_POST['category']) && $_POST['category'] === 'seeds') ? 'selected' : ''; ?>>Seeds</option>
            <option value="crops" <?php echo (isset($_POST['category']) && $_POST['category'] === 'crops') ? 'selected' : ''; ?>>Crops</option>
        </select><br>

        <label>Description:*</label><br>
        <textarea name="description" rows="4" cols="50" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea><br>

        <label>Price (TSh):*</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required><br>

        <label>Stock Quantity:*</label><br>
        <input type="number" name="stock" value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>" required><br>

        <label>Product Image:*</label><br>
        <input type="file" name="image" accept="image/*" required><br>

        <button type="submit">Save Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>