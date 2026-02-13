<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'carehospital';

// Establish database connection
$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate input fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    // Use prepared statement securely
    $stmt = $connection->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        die("Error preparing statement: " . htmlspecialchars($connection->error));
    }

    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Your message has been sent successfully!'); window.location.href='Contact.html';</script>";
    } else {
        echo "<script>alert('Error sending message: " . htmlspecialchars($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$connection->close();
?>
