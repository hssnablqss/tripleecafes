<?php
require 'db_connect.php'; // connects to Oracle

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM EMPLOYEES WHERE EMP_EMAIL = :u AND EMP_PASSWORD = :p";

$stid = oci_parse($conn, $sql);

oci_bind_by_name($stid, ":u", $email);
oci_bind_by_name($stid, ":p", $password);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);

if ($row) {
    session_start(); 
    $_SESSION['emp_id'] = $row['EMP_ID'];
    header("Location: ../admin/adminhome.php");
    exit();
} else {
    echo "❌ Invalid username or password.";
}
?>
