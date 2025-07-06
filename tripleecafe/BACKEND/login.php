<?php
require 'db_connect.php';

// $email = $_POST['email'];
// $password = $_POST['password'];
$email = trim($_POST['CUST_EMAIL']);
$password = trim($_POST['CUST_PASSWORD']);

//$email = $_POST['CUST_EMAIL'];
//$password = $_POST['CUST_PASSWORD'];

$sql = "SELECT * FROM CUSTOMERS WHERE CUST_EMAIL = :u AND CUST_PASSWORD = :p"; // Change to your table
$stid = oci_parse($conn, $sql);

oci_bind_by_name($stid, ":u", $email);
oci_bind_by_name($stid, ":p", $password);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);

if ($row) {
    header("Location: ../CustHome.html");
    exit();
} else {
    echo "❌ Invalid username or password.";
}

?>

