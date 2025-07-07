<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['cust_id'])) {
    header("Location: login.html");
    exit;
}

require 'BACKEND/db_connect.php';

$cust_id = $_SESSION['cust_id'];

$query = "
    SELECT od.order_id, m.menu_name, od.order_quantity, o.order_status
    FROM menu m
    JOIN order_details od ON m.menu_id = od.menu_id
    JOIN orders o ON od.order_id = o.order_id
    WHERE o.cust_id = :cust_id
";

$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ':cust_id', $cust_id);
oci_execute($stid);

$orders = [];
while ($row = oci_fetch_assoc($stid)) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Status</title>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/Order.css" />
</head>
<body>
    <div id="main-content" class="main-content">
        <!-- Top Nav -->
        <div class="topnav">
            <div class="nav-left">
                <img src="./img/LogoP1.png" alt="Logo" class="logo" />
                <a href="CustHome.php">Home</a> 
                <a href="order.php" class="active">Order Status</a>
            </div>
            <div class="nav-right">
                <a href="cart.html" class="icon-button">
                    <img src="./img/cartLogo(B).png" alt="Cart Icon" />
                    <span>Cart</span>
                </a>
                <div class="icon-button">
                    <button class="icon-button" onclick="toggleDropdown()">
                        <img src="./img/user-1.png" alt="user Icon" />
                        <span>User</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="update.php">Update Details</a>
                        <a href="logout.php">Log out</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="textbox">
            <div class="titlepage">
                <h2>Order Details</h2>
            </div>

            <div class="textbox2">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['ORDER_ID']) ?></td>
                                    <td><img src="./img/food3.png" alt="<?= htmlspecialchars($order['MENU_NAME']) ?>" class="order-img"></td>
                                    <td><?= htmlspecialchars($order['MENU_NAME']) ?></td>
                                    <td><?= htmlspecialchars($order['ORDER_QUANTITY']) ?></td>
                                    <td class="status <?= strtolower($order['ORDER_STATUS']) ?>">
                                        <?= htmlspecialchars($order['ORDER_STATUS']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No orders found for your account.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <h3>About Us</h3>
                <p>We are a passionate food and beverage provider!</p>
            </div>
            <div class="footer-content">
                <h3>Quick Links</h3>
                <ul class="list">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer-content">
                <h3>Follow Us</h3>
                <ul class="social-icons">
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="bottom-bar">
            <p>© 2025 Foodie. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
