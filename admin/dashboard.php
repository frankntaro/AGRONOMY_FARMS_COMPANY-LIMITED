<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div style="margin-left:220px; padding:20px;">
  <h2>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h2>
  <p>This is your admin dashboard. Use the sidebar to navigate.</p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>