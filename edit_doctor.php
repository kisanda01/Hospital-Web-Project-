<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'carehospital';

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$doctor_id = $_GET['id'] ?? null;
$name = $specialization = $contact = $email = '';

// Fetch existing doctor details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $doctor_id) {
    if (!is_numeric($doctor_id)) {
        die("Invalid doctor ID.");
    }

    $stmt = $connection->prepare("SELECT * FROM add_doctors WHERE doctor_id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $connection->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $specialization = $row['specialization'];
        $contact = $row['contact'];
        $email = $row['email'];
    } else {
        echo "<div class='alert alert-warning'>No doctor found with the given ID.</div>";
        $stmt->close();
        $connection->close();
        exit();
    }
    $stmt->close();
}

// Handle form submission for doctor update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['id'];
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($specialization) || empty($contact) || empty($email)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        $stmt = $connection->prepare("UPDATE add_doctors SET name = ?, specialization = ?, contact = ?, email = ? WHERE doctor_id = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $connection->error);
        }

        $stmt->bind_param("ssssi", $name, $specialization, $contact, $email, $doctor_id);

        if ($stmt->execute()) {
            echo "<script>alert('Doctor updated successfully'); window.location.href='manage_doctors.php';</script>";
        } else {
            echo "<div class='alert alert-danger'>Error updating doctor: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Doctor</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($doctor_id); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Doctor Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" name="specialization" id="specialization" class="form-control" value="<?php echo htmlspecialchars($specialization); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" id="contact" class="form-control" value="<?php echo htmlspecialchars($contact); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Doctor</button>
        </form>
    </div>
</body>
</html>
