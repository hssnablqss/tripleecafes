<?php
$email = $_POST['CUST_EMAIL'];
$password = $_POST['CUST_PASSWORD'];

// Mock user for testing
$mock_email = "test@example.com";
$mock_password = "password123";

if (empty($email) || empty($password)) {
    echo "❗ Email and password must not be empty.";
    exit;
}

if ($email === $mock_email && $password === $mock_password) {
    echo "✅ Login successful (mock data)!";
    header("Location: CustHome.html");
exit;
    // You can redirect or set a session here if needed
} else {
    echo "❌ Invalid username or password.";
}
?>
