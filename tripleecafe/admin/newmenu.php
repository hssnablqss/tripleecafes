<?php
require '../BACKEND/db_connect.php'; // Connect to Oracle

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'] ?? '';
    $menu_name = $_POST['menu_name'] ?? '';
    $menu_type = $_POST['menu_type'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';

    $sql = "INSERT INTO menu (menu_id, menu_price, menu_category, menu_desc, menu_name)
            VALUES (:menu_id, :menu_price, :menu_category, :menu_desc, :menu_name)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":menu_id", $menu_id);
    oci_bind_by_name($stid, ":menu_price", $price);
    oci_bind_by_name($stid, ":menu_category", $menu_type);
    oci_bind_by_name($stid, ":menu_desc", $description);
    oci_bind_by_name($stid, ":menu_name", $menu_name);

    if (oci_execute($stid)) {
        header("Location: adminmenu.php?added=1");
        exit;
    } else {
        $error = oci_error($stid);
        $errorMessage = "❌ Failed to insert: " . $error['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Add New Menu</title>
    <link rel="stylesheet" href="css/newmenu.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            <a href="adminreport.html">Report</a>
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

    <!-- FORM CARD -->
    <div class="card-wrapper">
        <div class="card">
            <div class="back-btn-container">
                <a href="adminmenu.php">
                    <button class="back-btn">Back</button>
                </a>
            </div>
            <h2>Add New Menu</h2>

            <?php if (isset($errorMessage)): ?>
                <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>

            <form action="newmenu.php" method="POST">
                <!-- Menu ID -->
                <div class="input-group">
                    <label for="menu_id">Menu ID</label>
                    <input type="text" id="menu_id" name="menu_id" placeholder="Enter Menu ID" required>
                </div>

                <!-- Menu Name -->
                <div class="input-group">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" id="menu_name" name="menu_name" placeholder="Enter Menu Name" required>
                </div>

                <!-- Menu Type -->
                <div class="input-group">
                    <label for="menu_type">Menu Type</label>
                    <input type="text" id="menu_type" name="menu_type" placeholder="Enter Menu Type" required>
                </div>

                <!-- Price -->
                <div class="input-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" placeholder="Enter Price" required step="0.01" min="0">
                </div>

                <!-- Description -->
                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Enter Description" required></textarea>
                </div>

                <!-- Submit Button -->
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

        if (window.location.href.includes("adminupddetails.html")) {
            document.getElementById("user-link").classList.add("active");
        }
    </script>
</body>
</html>
