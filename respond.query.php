<?php
// Start the session
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carehospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the query details for the given ID
    $sql = "SELECT id, name, email, type, message FROM feedback_queries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Query not found.");
    }
} else {
    die("Invalid request.");
}

// Handle form submission to update response
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = $_POST['response'];

    // Validate input
    if (empty($response)) {
        echo "<script>alert('Response cannot be empty!'); window.history.back();</script>";
    } else {
        // Update the feedback with a response
        $update_sql = "UPDATE feedback_queries SET response = ?, responded_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $response, $id);

        if ($stmt->execute()) {
            // Success: Redirect and show alert
            echo "<script>alert('Your response has been submitted successfully!'); window.location.href='patient_feedback.php?id=" . $id . "';</script>";
        } else {
            // Error: Show alert and go back
            echo "<script>alert('Error sending message: " . htmlspecialchars($stmt->error) . "'); window.history.back();</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Query</title>
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Respond to Feedback / Query</h3>

        <!-- Display success message -->
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Patient: <?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                <p class="card-text"><strong>Message:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                <form method="POST">
                    <div class="form-group">
                        <label for="response">Your Response:</label>
                        <textarea class="form-control" id="response" name="response" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Submit Response</button>
                </form>
            </div>
        </div>

        <a href="patient_feedback.php" class="btn btn-secondary mt-3">Back to Queries</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
