<?php
function sanitize($data) {
  return htmlspecialchars(trim($data));
}

function is_admin_logged_in() {
  return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>