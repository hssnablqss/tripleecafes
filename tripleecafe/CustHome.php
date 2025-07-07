<?php
require 'BACKEND/db_connect.php';

$query = "SELECT menu_id, menu_name, menu_desc, menu_category FROM menu ORDER BY menu_category";
$stid = oci_parse($conn, $query);
oci_execute($stid);

$menus = [];
while ($row = oci_fetch_assoc($stid)) {
    $menus[] = $row;
}

// Group menus by category
$grouped = [];
foreach ($menus as $menu) {
    $category = ucfirst(strtolower($menu['MENU_CATEGORY']));
    if (!isset($grouped[$category])) {
        $grouped[$category] = [];
    }
    $grouped[$category][] = $menu;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/CustIndex.css" />
    <style>
        .hidden-item {
            display: none;
        }
    </style>
</head>
<body>

<div class="topnav">
    <div class="nav-left">
        <img src="./img/LogoP1.png" alt="Logo" class="logo" />
        <a href="CustHome.php" class="active">Home</a>
        <a href="Order.php">Order Status</a>
    </div>
    <div class="nav-right">
        <a href="cart.html" class="icon-button" id="cart-link">
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
                <a href="login.html">Log out</a>
            </div>
        </div>
    </div>
</div>

<div class="textbox">
    <h2 style="text-align: center; margin-bottom: 5px; text-decoration: underline;">Our Menu</h2>

    <?php foreach ($grouped as $category => $items): ?>
        <div class="menu-section">
            <h3><?= htmlspecialchars($category) ?></h3>
            <div class="menu-grid" id="<?= strtolower($category) ?>">
                <?php foreach ($items as $index => $item): ?>
                    <div class="menu-item <?= $index >= 3 ? 'hidden-item' : '' ?>">
                        <img src="./img/food3.png">
                        <h4><?= htmlspecialchars($item['MENU_NAME']) ?></h4>
                        <p><?= htmlspecialchars($item['MENU_DESC']) ?></p>
                        <input type="number" min="1" value="1" class="qty-input" id="qty-<?= $item['MENU_NAME'] ?>">
                        <button class="add-to-cart-btn" onclick="addToCart('<?= $item['MENU_NAME'] ?>')">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($items) > 3): ?>
                <div class="show-more-container">
                    <button class="show-more-btn" onclick="toggleItems('<?= strtolower($category) ?>')">Show More ▼</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

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

<script>
function toggleItems(sectionId) {
    const section = document.getElementById(sectionId);
    const allItems = section.querySelectorAll('.menu-item');
    const btn = section.closest('.menu-section').querySelector('.show-more-btn');

    const isExpanded = btn.textContent.includes('Less');
    allItems.forEach((item, index) => {
        if (index >= 3) {
            item.classList.toggle('hidden-item', isExpanded);
        }
    });

    btn.textContent = isExpanded ? 'Show More ▼' : 'Show Less ▲';
}

function addToCart(itemName) {
    const qtyInput = document.getElementById(`qty-${itemName}`);
    const quantity = parseInt(qtyInput.value);

    if (isNaN(quantity) || quantity <= 0) {
        alert("Please enter a valid quantity.");
        return;
    }

    alert(`${quantity} x ${itemName} added to cart!`);
}
</script>

</body>
</html>
