<?php
$user = "CAFEUSER";
$pass = "CAFEUSER";
$host = "localhost:1521/FREEPDB1";
$conn = oci_connect($user, $pass, $host);

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM CUSTOMERS WHERE email = :u AND password = :p"; // Change to your table
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

