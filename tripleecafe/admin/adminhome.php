<?php
// Connect to Oracle
require '../BACKEND/db_connect.php'; // Adjust path if needed

// Fetch order data
$query = "SELECT o.order_id, o.cust_id, c.cust_name, o.order_status
          FROM orders o
          JOIN customers c ON o.cust_id = c.cust_id"; 
$stid = oci_parse($conn, $query);
oci_execute($stid);

$orders = [];
while ($row = oci_fetch_assoc($stid)) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/adminhome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* Scrollable table container */
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px;
        }

        .scrollable-table th, .scrollable-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .scrollable-table thead th {
            position: sticky;
            top: 0;
            background-color: #f9f9f9;
            z-index: 2;
        }

        .scrollable-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <!-- TOP NAV -->
    <div class="topnav">
        <div class="nav-left">
            <img src="./img/LogoP1.png" alt="Logo" class="logo" />
            <a href="adminhome.php" class="active">Home</a>
            <a href="adminmenu.php">Menu</a>
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

    <!-- Card Wrapper to Center the Card -->
    <div class="card-wrapper">
        <div class="card">
            <h2>Order's List of Customer</h2>

            <!-- Scrollable Table -->
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Order Id</th>
                            <th>Name</th>
                            <th>Orders Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($order['ORDER_ID']) ?></td>
                                <td><?= htmlspecialchars($order['CUST_NAME']) ?></td>
                                <td>
                                    <a href="order-details.php?order_id=<?= urlencode($order['ORDER_ID']) ?>">
                                        <button class="btn">Order's Details</button>
                                    </a>
                                </td>
                                <td class="status preparing"><?= htmlspecialchars($order['ORDER_STATUS']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.querySelector('.dropdown-content');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        document.addEventListener('click', function(event) {
            const isClickInside = document.querySelector('.icon-button').contains(event.target);
            if (!isClickInside) {
                const dropdown = document.querySelector('.dropdown-content');
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<script>
    const params = new URLSearchParams(window.location.search);
    if (params.get("success") === "updated") {
        alert("Employee successfully updated!");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
  </script>