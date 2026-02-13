<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form inputs
    $invoiceID = $_POST["invoice"];
    $amount = $_POST["amount"];
    $paymentMethod = $_POST["method"];
    $cardDetails = $_POST["card"];

    // Validate inputs (you can add more validation based on your needs)
    if (empty($invoiceID) || empty($amount) || empty($paymentMethod)) {
        echo "<script>alert('Please fill in all the required fields.'); window.location.href='payment.html';</script>";
        exit();
    }

    // Database connection
    $connection = new mysqli('localhost', 'root', '', 'carehospital');

    // Check for database connection errors
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Insert payment record
    $stmt = $connection->prepare("INSERT INTO payment (invoice, amount, method, card) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error preparing the statement: " . $connection->error);
    }

    // Bind parameters (s = string, d = decimal)
    $stmt->bind_param("sdss", $invoiceID, $amount, $paymentMethod, $cardDetails);

    // Execute and provide user feedback
    if ($stmt->execute()) {
        echo "<script>alert('Payment successful! Thank you.'); window.location.href='payment.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Update appointment status to "Paid"
    $updateStatus = $connection->prepare("UPDATE appointment SET status = 'Paid' WHERE invoice = ?");
    $updateStatus->bind_param("s", $invoiceID);

    if (!$updateStatus->execute()) {
        echo "Error updating appointment status: " . $updateStatus->error;
    }

    // Close connections
    $stmt->close();
    $updateStatus->close();
    $connection->close();
}
?>
