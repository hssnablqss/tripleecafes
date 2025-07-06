<?php
// Connect to Oracle
require '../BACKEND/db_connect.php'; // Adjust path if needed

// Fetch menu data
$query = "SELECT menu_id, menu_category, menu_name, menu_desc, menu_price FROM menu";
$stid = oci_parse($conn, $query);
oci_execute($stid);

$menus = [];
while ($row = oci_fetch_assoc($stid)) {
    $menus[] = $row;
}

// If delete is triggered
if (isset($_GET['delete_menu_id'])) {
    $delete_id = $_GET['delete_menu_id'];

    $delete_sql = "DELETE FROM menu WHERE menu_id = :id";
    $delete_stid = oci_parse($conn, $delete_sql);
    oci_bind_by_name($delete_stid, ":id", $delete_id);
    $delete_result = oci_execute($delete_stid);

    if ($delete_result) {
        header("Location: adminmenu.php?deleted=1");
        exit;
    } else {
        $error = oci_error($delete_stid);
        echo "Failed to delete: " . $error['message'];
    }
}

// Fetch menu data
$query = "SELECT menu_id, menu_category, menu_name, menu_desc, menu_price FROM menu";
$stid = oci_parse($conn, $query);
oci_execute($stid);

$menus = [];
while ($row = oci_fetch_assoc($stid)) {
    $menus[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="css/adminmenu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        #user-link.active {
            background-color: #f1c40f;
            color: #fff;
        }

        .table-scrollable {
            max-height: 400px;
            overflow-y: auto;
        }

        table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- TOP NAV -->
    <div class="topnav">
        <div class="nav-left">
            <img src="./img/LogoP1.png" alt="Logo" class="logo" />
            <a href="adminhome.php">Home</a>
            <a href="adminmenu.php" class="active">Menu</a>
            <a href="adminreport.php">Report</a>
        </div>
        <div class="nav-right">
            <div class="icon-button">
                <button class="icon-button" id="user-link" onclick="toggleDropdown()">
                    <img src="./img/user-1.png" alt="user Icon" />
                    <span>User</span>
                </button>
                <div class="dropdown-content">
                    <a href="adminupddetails.html">Update Details</a>
                    <a href="adminlogin.html">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="card-wrapper">
        <div class="card">
            <h2>List of Menu</h2>

            <!-- Filter Buttons + Add New -->
            <div class="save-btn-container">
                <div class="dropdown">
                    <button class="filter-btn">Filter Menu</button>
                    <div class="dropdown-content">
                        <button onclick="filterMenuByType('Food')">Filter by Food</button>
                        <button onclick="filterMenuByType('Drink')">Filter by Drink</button>
                        <button onclick="filterMenuByType('Dessert')">Filter by Dessert</button>
                    </div>
                </div>
                <a href="newmenu.php" class="save-btn">Add New Menu</a>
            </div>

            <!-- TABLE -->
            <div class="table-scrollable">
                <table id="menuTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu ID</th>
                            <th>Menu Type</th>
                            <th>Menu Name</th>
                            <th>Picture</th>
                            <th>Description</th>
                            <th>Price (RM)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($menus as $menu): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($menu['MENU_ID']) ?></td>
                                <td><?= htmlspecialchars($menu['MENU_CATEGORY']) ?></td>
                                <td><?= htmlspecialchars($menu['MENU_NAME']) ?></td>
                                <td><img src="../img/food3.png" alt="menu"></td>
                                <td><?= htmlspecialchars($menu['MENU_DESC']) ?></td>
                                <td><?= number_format($menu['MENU_PRICE'], 2) ?></td>
                                <td>
                                    <a href="menuupddetails.php?menu_id=<?= urlencode($menu['MENU_ID']) ?>" class="save-btn update">Update</a>
                                    <a href="adminmenu.php?delete_menu_id=<?= urlencode($menu['MENU_ID']) ?>" class="save-btn delete" onclick="return confirmDelete()">Delete</a>
                                </td>
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

        document.addEventListener('click', function (event) {
            const isClickInside = document.querySelector('.icon-button').contains(event.target);
            if (!isClickInside) {
                const dropdown = document.querySelector('.dropdown-content');
                dropdown.style.display = 'none';
            }
        });

        function filterMenuByType(type) {
            const rows = document.querySelectorAll('#menuTable tbody tr');
            rows.forEach(row => {
                const menuType = row.cells[2].textContent.trim();
                row.style.display = (menuType === type || type === 'All') ? '' : 'none';
            });
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this item?");
        }
    </script>
</body>
</html>
