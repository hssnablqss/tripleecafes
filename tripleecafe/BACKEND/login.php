<?php
require 'db_connect.php';

// $email = $_POST['email'];
// $password = $_POST['password'];

$email = $_POST['email'] ?? 'test@example.com';
$password = $_POST['password'] ?? 'secret';

$sql = "SELECT * FROM CUSTOMERS WHERE CUST_EMAIL = :u AND CUST_PASSWORD = :p"; // Change to your table
$stid = oci_parse($conn, $sql);

oci_bind_by_name($stid, ":u", $email);
oci_bind_by_name($stid, ":p", $password);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);

if ($row) {
    echo "✅ Login successful!";
} else {
    echo "❌ Invalid username or password.";
}
?>

