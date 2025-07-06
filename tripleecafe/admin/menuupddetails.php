<?php
require '../BACKEND/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu_id = $_POST['menu_id'] ?? '';
    $menu_name = $_POST['menu-name'] ?? '';
    $menu_type = $_POST['menu-type'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';

    $sql = "UPDATE menu 
            SET menu_price = :price,
                menu_category = :type,
                menu_desc = :description_text,
                menu_name = :name
            WHERE menu_id = :id";

    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":id", $menu_id);
    oci_bind_by_name($stid, ":name", $menu_name);
    oci_bind_by_name($stid, ":type", $menu_type);
    oci_bind_by_name($stid, ":price", $price);
    oci_bind_by_name($stid, ":description_text", $description);

    $result = oci_execute($stid);

    if ($result) {
        header("Location: adminmenu.php?updated=1");
        exit;
    } else {
        $error = oci_error($stid);
        echo "❌ Database error: " . $error['message'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Update Menu</title>
    <link rel="stylesheet" href="css/updatenewmenu.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        #user-link.active {
            background-color: #f1c40f;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- TOP NAV -->
    <div class="topnav">
        <div class="nav-left">
            <img src="./img/LogoP1.png" alt="Logo" class="logo" />
            <a href="adminhome.php">Home</a>
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
                    <a href="adminupddetails.html">Update Details</a>
                    <a href="adminhome.php">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-wrapper">
        <div class="card">
            <div class="back-btn-container">
                <a href="adminmenu.php">
                    <button class="back-btn">Back</button>
                </a>
            </div>
            <h2>Update Menu's Details</h2>
            <form action="menuupddetails.php" method="POST">
                <div class="input-group">
                    <label for="menu_id">Menu ID</label>
                    <input type="text" id="menu_id" name="menu_id" placeholder="Enter Menu ID" required />
                </div>
                <div class="input-group">
                    <label for="menu-name">Menu Name</label>
                    <input type="text" id="menu-name" name="menu-name" placeholder="Enter Menu Name" required />
                </div>
                <div class="input-group">
                    <label for="menu-type">Menu Type</label>
                    <input type="text" id="menu-type" name="menu-type" placeholder="Enter Menu Type" required />
                </div>
                <div class="input-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" placeholder="Enter Price" required step="0.01" min="0" />
                </div>
                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Enter Description" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.querySelector(".dropdown-content");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        document.addEventListener("click", function (event) {
            const isClickInside = document.querySelector(".icon-button").contains(event.target);
            if (!isClickInside) {
                const dropdown = document.querySelector(".dropdown-content");
                dropdown.style.display = "none";
            }
        });
    </script>
</body>

</html>
