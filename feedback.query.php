<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carehospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Handle feedback form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $message = $conn->real_escape_string(trim($_POST['feedback'] ?? $_POST['query']));
    $type = isset($_POST['feedback']) ? 'feedback' : 'query';

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

    $sql = "INSERT INTO feedback_queries (name, email, type, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $type, $message);
        if ($stmt->execute()) {
            echo "<script>alert('Your $type has been submitted successfully.'); window.location.href='feedback.query.html';</script>";
        } else {
            echo "<script>alert('Failed to submit $type. Please try again later.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please contact support.'); window.history.back();</script>";
    }
}

$conn->close();
?>
