<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['pno'];
    $gender = $_POST['gen'];
    $doctor = $_POST['doc'];
    $date = $_POST['dates'];
    $time = $_POST['times'];

    // Generate a unique invoice ID (reduced length)
    $invoiceID = "INV-" . strtoupper(substr(sha1(uniqid()), 0, 6));

    // Validate email length (assumes column length is 255)
    if (strlen($email) > 255) {
        echo "Error: Email is too long. Please use an email under 255 characters.";
        exit();
    }

    try {
        // Database connection
        $pdo = new PDO('mysql:host=localhost;dbname=carehospital', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert appointment data
        $stmt = $pdo->prepare("INSERT INTO appointment (fname, email, pno, gen, doc, dates, times, invoice_id)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $gender, $doctor, $date, $time, $invoiceID]);

        // Modal and confirmation message
        echo "
        <!-- Modal HTML -->
        <div class='modal fade' id='appointmentModal' tabindex='-1' aria-labelledby='appointmentModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='appointmentModalLabel'>Appointment Confirmation</h5>
                    </div>
                    <div class='modal-body'>
                        <h5>Thank you, $name!</h5>
                        <p>Your appointment has been successfully booked.</p>
                        <ul>
                            <li><strong>Invoice ID:</strong> $invoiceID</li>
                            <li><strong>Doctor:</strong> $doctor</li>
                            <li><strong>Date:</strong> $date</li>
                            <li><strong>Time Slot:</strong> $time</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Display the modal on page load
            window.onload = function() {
                var myModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                myModal.show();
            }
        </script>
        ";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
