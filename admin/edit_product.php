<?php
include 'auth_check.php';
include 'includes/db.php';

$id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle image upload
    $imagePath = $product['image']; // default: keep existing
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $newName = time() . "_" . $imageName;
        $targetFile = $targetDir . $newName;

        if (move_uploaded_file($imageTmp, $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // Update product
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("ssdiss", $name, $category, $price, $stock, $imagePath, $id);
    $stmt->execute();

    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F0F5E6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .form-container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
            text-align: left;
        }
        h2 {
            color: #2F4F2F;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #556B2F;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #C0D6BA;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="file"] {
            padding: 5px;
        }
        button[type="submit"] {
            background-color: #556B2F;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #2F4F2F;
        }
        img.preview {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="form-container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <?php
                        $categories = ['tools', 'equipments', 'fertilizers', 'pesticides', 'herbicides', 'insecticides', 'seeds', 'crops'];
                        foreach ($categories as $cat) {
                            $selected = ($product['category'] === $cat) ? 'selected' : '';
                            echo "<option value='$cat' $selected>$cat</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
            </div>

            <div class="form-group">
    <label>Current Image:</label>
   <?php
    if (!empty($product['image'])) {
        $imagePath = $product['image'];

        // Extract just the filename from the path saved in DB
        $imageFileName = basename($imagePath);

        // Build the correct web-accessible path relative to current file
        $webImagePath = "uploads/" . $imageFileName;

        // Check if the physical file exists
        if (!file_exists(__DIR__ . "/uploads/" . $imageFileName)) {
            echo "<p style='color:red;'>Image file not found at: uploads/$imageFileName</p>";
        }

        echo "<img src='$webImagePath' alt='Product Image' class='preview'>";
    } else {
        echo "<p>No image uploaded.</p>";
    }
?>
 <div class="form-group">
                <label for="image">Upload New Image (optional):</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>

            <button type="submit">Update Product</button>
        </form>
    </div>
   
</div>
 <?php include 'footer.php'; ?>
</body>

</html>
