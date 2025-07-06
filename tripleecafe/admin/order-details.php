<?php
require '../BACKEND/db_connect.php';

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    echo "❌ No order ID provided.";
    exit;
}

$sql = "SELECT o.order_id, o.order_status, TO_CHAR(o.order_date, 'Month DD, YYYY') as order_date,
               TO_CHAR(o.order_time, 'HH24:MI') as order_time, m.menu_name, d.unit_price, d.order_quantity
        FROM orders o JOIN order_details d ON o.order_id = d.order_id
        JOIN menu m on d.menu_id = m.menu_id
        WHERE o.order_id = :oid";


$sql = "SELECT ORDER_TOTAL FROM ORDERS";

$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":oid", $order_id);
oci_execute($stid);

$items = [];
while ($row = oci_fetch_assoc($stid)) {
    $items[] = $row;
}

if (empty($items)) {
    echo "❌ Order not found.";
    exit;
}

$first = $items[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/ordersdetails.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="topnav">
        <div class="nav-left">
            <img src="./img/LogoP1.png" alt="Logo" class="logo" />
            <a href="adminhome.php">Home</a>
            <a href="adminmenu.html" class="active">Menu</a>
            <a href="adminreport.php">Report</a>
        </div>
        <div class="nav-right">
            <div class="icon-button">
                <button class="icon-button" id="user-link" onclick="toggleDropdown()">
                    <img src="./img/user-1.png" alt="user Icon" />
                    <span>User</span>
                </button>
                <div class="dropdown-content">
                    <a href="adminupdate.php">Update Details</a>
                    <a href="adminlogin.html">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-wrapper">
        <div class="receipt-card">
            <h3>Order's Details</h3>
            <hr>
            <div class="order-info">
                <div class="order-left">
                    <p><strong>Order ID:</strong> <?= htmlspecialchars($first['ORDER_ID']) ?></p>
                    <p class="order-status"><strong>Order Status:</strong> <?= htmlspecialchars($first['ORDER_STATUS']) ?></p>
                </div>
                <div class="order-right">
                    <p><strong>Order Date:</strong> <?= htmlspecialchars($first['ORDER_DATE']) ?></p>
                    <p><strong>Order Time:</strong> <?= htmlspecialchars($first['ORDER_TIME']) ?></p>
                </div>
            </div>

            <ul class="receipt-list">
                <li><span>Menu</span><span>Price</span></li>
                <?php foreach ($items as $item): ?>
                    <li><span><?= htmlspecialchars($item['MENU_NAME']) ?> x<?= $item['ORDER_QUANTITY'] ?></span><span>RM <?= number_format($item['UNIT_PRICE'], 2) ?></span></li>
                <?php endforeach; ?>
            </ul>

            <div class="total-section">
                <span>Total Amount: </span>
                <select class="status-dropdown">
                    <option value="preparing" <?= $first['ORDER_STATUS'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                    <option value="finished" <?= $first['ORDER_STATUS'] === 'finished' ? 'selected' : '' ?>>Finished</option>
                    <option value="cancelled" <?= $first['ORDER_STATUS'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.querySelector('.dropdown-content');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        document.addEventListener('click', function (event) {
            const isClickInside = document.querySelector('.icon-button').contains(event.target);
            if (!isClickInside) {
                document.querySelector('.dropdown-content').style.display = 'none';
            }
        });
    </script>
</body>
</html>
