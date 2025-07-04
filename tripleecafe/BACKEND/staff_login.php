<?php
require 'db_connect.php'; // connects to Oracle

$email = $_POST['email'] ?? 'staff@example.com';
$password = $_POST['password'] ?? 'staffpass';

$sql = "SELECT * FROM EMPLOYEES WHERE EMP_EMAIL = :u AND EMP_PASSWORD = :p";

$stid = oci_parse($conn, $sql);

oci_bind_by_name($stid, ":u", $email);
oci_bind_by_name($stid, ":p", $password);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);

if ($row) {
    echo "✅ Staff login successful!";
} else {
    echo "❌ Invalid staff credentials.";
}
?>
