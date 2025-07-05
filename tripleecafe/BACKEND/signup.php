<?php
require 'db_connect.php';

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

        header("Location: ../signup.html?error=password_mismatch&name=$name&email=$email&phone=$phone");
        exit;
    }

    // Optional: Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO CUSTOMERS (CUST_ID, CUST_NAME, CUST_PHONENUM, CUST_EMAIL, CUST_PASSWORD)
            VALUES (CUST_SEQ.NEXTVAL, :name, :phone, :email, :password)";

    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":name", $name);
    oci_bind_by_name($stid, ":phone", $phone);
    oci_bind_by_name($stid, ":email", $email);
    oci_bind_by_name($stid, ":password", $hashedPassword);

    $result = oci_execute($stid);

    if ($result) {
    header("Location: ../login.html?success=registered");
    exit;   
    } 
    else {
        $e = oci_error($stid);
        echo "Error inserting data: " . $e['message'];
    }

    oci_free_statement($stid);
    oci_close($conn);
} else {
    echo "Invalid access.";
}
?>
