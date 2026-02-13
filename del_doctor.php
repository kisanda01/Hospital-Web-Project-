<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'carehospital';

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET['id'])) {
    $doctor_id = intval($_GET['id']); // Ensure a valid integer ID is used

    // Corrected DELETE query
    $stmt = $connection->prepare("DELETE FROM add_doctors WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor deleted successfully'); window.location.href='manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor'); window.history.back();</script>";
    }

    $stmt->close();
}

$connection->close();
?>
