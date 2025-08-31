<?php
// This file assumes 'auth_check.php' handles user authentication and session management.
include 'auth_check.php';
include './includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            position: relative;
        }
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }
            .header-logo { margin-left: 0; margin-bottom: 10px; }
            .header-title { position: static; transform: none; text-align: center; margin-bottom: 10px; }
            .notification-icon { margin-right: 0; }
        }
        .header-logo { height: 50px; width: auto; border-radius: 20px; margin-left: 50px; }
        .header-title { position: absolute; left: 50%; transform: translateX(-50%); margin: 0; text-align: center; font-size: 1.5rem; }
        .notification-icon { position: relative; display: inline-block; margin-right: 50px; cursor: pointer; }
        .notification-icon i { font-size: 24px; color: white; }
        .notification-badge {
            position: absolute; top: -8px; right: -8px;
            background-color: red; color: white;
            border-radius: 50%; padding: 4px 6px;
            font-size: 12px; font-weight: bold;
            text-align: center; display: none;
        }
        .notification-dropdown {
            position: absolute; top: 100%; right: 0;
            background-color: white; color: black;
            border: 1px solid #ccc; border-radius: 4px;
            padding: 10px; min-width: 250px;
            display: none; z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .notification-dropdown.show { display: block; }
        .notification-item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .notification-item:last-child { border-bottom: none; }
        .notification-item a { text-decoration: none; color: #333; display: block; }
        .notification-item a:hover { color: #2e7d32; }
    </style>
</head>
<body>

<div class="header-container">
    <a href="dashboard.php" class="header-logo-link">
        <img src="../IMAGES/LOGOO.png" alt="Agronomy Farms Logo" class="header-logo">
    </a>
    <h1 class="header-title">Agronomy Farms Admin Dashboard</h1>
    <div class="notification-icon" onclick="toggleNotifications(event)">
        <i class="fas fa-bell"></i>
        <span class="notification-badge" id="notificationBadge"></span>
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-item">Loading notifications...</div>
        </div>
    </div>
</div>

<script>
    function fetchAndRenderNotifications() {
        const dropdown = document.getElementById("notificationDropdown");
        const badge = document.getElementById("notificationBadge");

        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                let totalNotifications = 0;
                let dropdownContent = '';

                if (data.error) {
                    dropdownContent = `<div class="notification-item">Error: ${data.error}</div>`;
                } else {
                    let hasNotifications = false;

                    if (data.new_applications > 0) {
                        dropdownContent += `<div class="notification-item">
                            <a href="applications.php" onclick="clearNotifications('applications')">
                                New Applications: ${data.new_applications}
                            </a>
                        </div>`;
                        totalNotifications += data.new_applications;
                        hasNotifications = true;
                    }

                    if (data.new_messages > 0) {
                        dropdownContent += `<div class="notification-item">
                            <a href="messages.php" onclick="clearNotifications('messages')">
                                New Messages: ${data.new_messages}
                            </a>
                        </div>`;
                        totalNotifications += data.new_messages;
                        hasNotifications = true;
                    }

                    if (data.new_orders > 0) {
                        dropdownContent += `<div class="notification-item">
                            <a href="orders.php" onclick="clearNotifications('orders')">
                                New Orders: ${data.new_orders}
                            </a>
                        </div>`;
                        totalNotifications += data.new_orders;
                        hasNotifications = true;
                    }

                    if (!hasNotifications) {
                        dropdownContent = `<div class="notification-item">No new notifications</div>`;
                    }
                }

                if (totalNotifications > 0) {
                    badge.textContent = totalNotifications;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }

                dropdown.innerHTML = dropdownContent;
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                dropdown.innerHTML = '<div class="notification-item">Could not fetch notifications.</div>';
            });
    }

    function toggleNotifications(event) {
        event.stopPropagation();
        const dropdown = document.getElementById("notificationDropdown");
        dropdown.classList.toggle("show");
    }

    function clearNotifications(type) {
        fetch('clear_notifications.php?type=' + type)
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                fetchAndRenderNotifications(); // Refresh counts after clearing
            })
            .catch(error => console.error('Error clearing ' + type + ' notifications:', error));
    }

    window.onclick = function(event) {
        if (!event.target.closest('.notification-icon')) {
            const dropdown = document.getElementById("notificationDropdown");
            dropdown.classList.remove('show');
        }
    };

    document.addEventListener("DOMContentLoaded", fetchAndRenderNotifications);
    // setInterval(fetchAndRenderNotifications, 60000); // optional auto-refresh
</script>

</body>
</html>
