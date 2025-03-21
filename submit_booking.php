<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $department_id = $_POST['department_id'];
    $reason_id = $_POST['reason_id'];
    $booking_date = $_POST['booking_date'];

    $stmt = $conn->prepare(query: "INSERT INTO bookings (first_name, last_name, contact_number, email_address, department_id, reason_id, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $first_name, $last_name, $contact_number, $email_address, $department_id, $reason_id, $booking_date);

    if ($stmt->execute()) {
        echo "Booking submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>