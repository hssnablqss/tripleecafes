<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['empname'];
    $email    = $_POST['empemail'];
    $phone    = $_POST['empphone'];
    $role     = $_POST['emprole'];
    $gender   = $_POST['empgender'];
    $hiredate = $_POST['emphiredate'];
    $password = $_POST['emppassword'];
    $confirm  = $_POST['conpass'];

    if ($password !== $confirm) {
        // Redirect back with query params
        $name     = urlencode($name);
        $email    = urlencode($email);
        $phone    = urlencode($phone);
        $hiredate = urlencode($hiredate);
        $gender   = urlencode($gender);

        header("Location: ../admin/adminsignup.html?error=password_mismatch&empname=$name&empemail=$email&empphone=$phone&emphiredate$hiredate&emprole=$role&empgender=$gender");
        exit;
    }

    // SQL query: placeholders must match exactly
    $sql = "INSERT INTO EMPLOYEES (EMP_ID, EMP_NAME, EMP_PHONENUM, EMP_EMAIL, EMP_ROLE, EMP_HIREDATE, EMP_PASSWORD, EMP_GENDER)
            VALUES (CUST_SEQ.NEXTVAL, :empname, :empphone, :empemail, :emprole, TO_DATE(:emphiredate, 'YYYY-MM-DD'), :emppassword, :empgender)";

    $stid = oci_parse($conn, $sql);

    // Correctly bind all placeholders
    oci_bind_by_name($stid, ":empname", $name);
    oci_bind_by_name($stid, ":empphone", $phone);
    oci_bind_by_name($stid, ":empemail", $email);
    oci_bind_by_name($stid, ":emprole", $role);
    oci_bind_by_name($stid, ":emphiredate", $hiredate);
    oci_bind_by_name($stid, ":emppassword", $password);
    oci_bind_by_name($stid, ":empgender", $gender);
    $result = oci_execute($stid);

    if ($result) {
        header("Location: ../admin/adminlogin.html?success=registered");
        exit;   
    } else {
        $e = oci_error($stid);
        echo "Error inserting data: " . $e['message'];
    }

    oci_free_statement($stid);
    oci_close($conn);
} else {
    echo "Invalid access.";
}
?>