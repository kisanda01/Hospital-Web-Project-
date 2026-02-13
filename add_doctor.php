<?php
session_start();

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'carehospital';

// Establish database connection
$connection = new mysqli($host, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $doctor_id = $_POST['doctor_id'];
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $create_at = $_POST['create_at']; // Ensure this field is provided in the form

    // Use prepared statements for secure queries
    $stmt = $connection->prepare("INSERT INTO add_doctors (doctor_id, name, specialization, contact, email, create_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $doctor_id, $name, $specialization, $contact, $email, $create_at);

    if ($stmt->execute()) {
        echo "<script>alert('New doctor added successfully.'); window.location.href='manage_doctors.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Add New Doctor</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="doctor_id" class="form-label">Doctor ID</label>
                <input type="text" name="doctor_id" id="doctor_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Doctor Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" name="specialization" id="specialization" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" id="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="create_at" class="form-label">Created At</label>
                <input type="datetime-local" name="create_at" id="create_at" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Doctor</button>
        </form>
    </div>
</body>
</html>