<?php
session_start();
require 'BACKEND/db_connect.php';
$custID = $_SESSION['cust_id']; 

if (!isset($_SESSION['cust_id'])) {
    echo "You must be logged in.";
    exit;
}

$custID = $_SESSION['cust_id'];

$sql = "SELECT CUST_NAME, CUST_EMAIL, CUST_PHONENUM FROM CUSTOMERS WHERE CUST_ID = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $custID);
oci_execute($stmt);

$row = oci_fetch_assoc($stmt);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm = $_POST['conpass'];

    if ($password !== $confirm) {
        $name = urlencode($name);
        $email = urlencode($email);
        $phone = urlencode($phone);
        header("Location: update.php?error=password_mismatch&name=$name&email=$email&phone=$phone");
        exit;
    }

    // Determine the SQL and bind accordingly
    if (!empty($password)) {
        $sql = "UPDATE CUSTOMERS
                SET CUST_NAME = :name,
                    CUST_EMAIL = :email,
                    CUST_PHONENUM = :phone,
                    CUST_PASSWORD = :password
                WHERE CUST_ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":password", $password);
    } else {
        $sql = "UPDATE CUSTOMERS
                SET CUST_NAME = :name,
                    CUST_EMAIL = :email,
                    CUST_PHONENUM = :phone
                WHERE CUST_ID = :id";
        $stmt = oci_parse($conn, $sql);
    }

    // Common bindings
    oci_bind_by_name($stmt, ":name", $name);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":phone", $phone);
    oci_bind_by_name($stmt, ":id", $custID);

    if (oci_execute($stmt)) {
        header("Location: CustHome.html?success=updated");
        exit;
    } else {
        $e = oci_error($stmt);
        echo "Update failed: " . $e['message'];
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>

<!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width,initial-scale=1.0">
            <title>Update Your Details</title>
            <link rel="stylesheet" href="css/updatestyle.css">
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
                <style>
                /* Style for active cart link */
                #user-link.active {
                    background-color: #f1c40f;  /* Highlight color */
                    color: #fff;
                }
            </style>
        </head>

        <body>
            <!-- TOP NAV -->
            <div class="topnav">
                <div class="nav-left">
                    <img src="./img/LogoP1.png" alt="Logo" class="logo" />
                    <a href="CustHome.html">Home</a>
                    <a href="Order.html">Order Status</a>
                </div>       
                <div class="nav-right">
                                    <a href="cart.html" class="icon-button" id="cart-link">
                            <img src="./img/cartLogo(B).png" alt="Cart Icon" />
                            <span>Cart</span>
                        </a> 
                    <div class="icon-button">
                        <button class="icon-button"id="user-link" onclick="toggleDropdown()">
                            <img src="./img/user-1.png" alt="user Icon" />
                            <span>User</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="update.php">Update Details</a>
                            <a href="CustHome.html">Log out</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- UPDATE FORM -->
            <div class="wrapper">
                <form method="POST">
                    <h1>Update Details</h1>

                    <div class="input-box">
                        <div class="input-field">
                            <input type="text" name="fullname" value="<?= $row['CUST_NAME'] ?? '' ?>" required>
                            <i class='bx bxs-user'></i>
                        </div>
                    </div>

                    <div class="input-box">
                        <div class="input-field">
                            <input type="email" name="email" value="<?= $row['CUST_EMAIL'] ?? '' ?>" required>
                            <i class='bx bxs-envelope'></i>
                        </div>
                        <div class="input-field">
                            <input type="number" name="phone" value="<?= $row['CUST_PHONENUM'] ?? '' ?>" required>
                            <i class='bx bxs-phone'></i>
                        </div>
                    </div>

                    <div class="input-box">
                        <div class="input-field">
                            <input type="password" name="password" placeholder="New Password">
                            <i class='bx bxs-lock-alt'></i>
                        </div>
                        <div class="input-field">
                            <input type="password" name="conpass" placeholder="Confirm Password">
                            <i class='bx bxs-lock-alt'></i>
                            <div id="password-error" style="color: red; font-size: 13px; margin-top: 5px; display: none;">
                                Passwords do not match.
                            </div>
                        </div>
                    </div>

                    <label><input type="checkbox" required>I hereby declare that the above information is true and correct.</label>
                    <button type="submit" class="btn">Update Details</button>
                </form>
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
                // Check if the current page is the Cart page
                if (window.location.href.indexOf("update.php") !== -1) {
                    // Add active class to Cart link
                    document.getElementById("user-link").classList.add("active");
                }

                // Optional: Close the dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const isClickInside = document.querySelector('.icon-button').contains(event.target);
            if (!isClickInside) {
                const dropdown = document.querySelector('#userIcon .dropdown-content');
                dropdown.style.display = 'none';
            }
        });
        
            </script>

        </body>
    </html>

<script>
const params = new URLSearchParams(window.location.search);
if (params.get("error") === "password_mismatch") {
    const errorDiv = document.getElementById("password-error");
    if (errorDiv) {
        errorDiv.style.display = "block";
    }
}
</script>
