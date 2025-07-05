<?php
$user = "CAFEUSER";
$pass = "CAFEUSER";
$host = "localhost/FREEPDB1";

$conn = oci_connect($user, $pass, $host);

if (!$conn) {
    $e = oci_error();
    die("❌ Failed to connect to Oracle: " . $e['message']);
}
?>
