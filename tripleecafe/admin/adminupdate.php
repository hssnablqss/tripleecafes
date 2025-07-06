<?php
session_start();
require '../BACKEND/db_connect.php';
$empID = $_SESSION['emp_id']; 

if (!isset($_SESSION['emp_id'])) {
    echo "You must be logged in.";
    exit;
}

$empID = $_SESSION['emp_id'];

$sql = "SELECT EMP_NAME, EMP_EMAIL, EMP_PHONENUM FROM EMPLOYEES WHERE EMP_ID = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $empID);
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
        header("Location: adminupdate.php?error=password_mismatch&name=$name&email=$email&phone=$phone");
        exit;
    }

    // Determine the SQL and bind accordingly
    if (!empty($password)) {
        $sql = "UPDATE EMPLOYEES
                SET EMP_NAME = :name,
                    EMP_EMAIL = :email,
                    EMP_PHONENUM = :phone,
                    EMP_PASSWORD = :password
                WHERE EMP_ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":password", $password);
    } else {
        $sql = "UPDATE EMPLOYEES
                SET EMP_NAME = :name,
                    EMP_EMAIL = :email,
                    EMP_PHONENUM = :phone
                WHERE EMP_ID = :id";
        $stmt = oci_parse($conn, $sql);
    }

    // Common bindings
    oci_bind_by_name($stmt, ":name", $name);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":phone", $phone);
    oci_bind_by_name($stmt, ":id", $empID);

    if (oci_execute($stmt)) {
        header("Location: adminhome.php?success=updated");
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
    <link rel="stylesheet" href="css/adminupddetails.css">
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
            <a href="adminhome.html">Home</a>
            <a href="adminmenu.html">Menu</a>
            <a href="adminreport.html">Report</a>

        </div>
        <div class="nav-right">
            <div class="icon-button">
                        <button class="icon-button"id="user-link" onclick="toggleDropdown()">
                            <img src="./img/user-1.png" alt="user Icon" />
                            <span>User</span>
                        </button>
                <div class="dropdown-content">
                    <a href="adminupdate.php">Update Details</a>
                    <a href="adminhome.html">Log out</a>
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
                    <input type="text" name="fullname" value="<?= $row['EMP_NAME'] ?? '' ?>" required>
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <div class="input-box">
                <div class="input-field">
                    <input type="email" name="email" value="<?= $row['EMP_EMAIL'] ?? '' ?>" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-field">
                    <input type="number" name="phone" value="<?= $row['EMP_PHONENUM'] ?? '' ?>" required>
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

            <label><input type="checkbox">I hereby declare that the above information is true and correct.</label>

            <button type="submit" class="btn">Update Details</button>
            <button type="submit" class="delbtn">Delete Acount</button>
        </form>
    </div>

            <script>
                // Check if the current page is the Cart page
                if (window.location.href.indexOf("adminupdate.php") !== -1) {
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
