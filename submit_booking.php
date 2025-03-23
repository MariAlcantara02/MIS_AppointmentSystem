<?php
session_start();
$conn = new mysqli("localhost", "root", "", "appointment_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Unauthorized access. Please log in.");
}

// Get form data
$department_id = $_POST['department_id'];
$reason_id = $_POST['reason_id'];
$booking_date = $_POST['booking_date'];

// Insert booking into database with default status 'Pending'
$stmt = $conn->prepare("INSERT INTO bookings (user_id, department_id, reason_id, booking_date, status) VALUES (?, ?, ?, ?, 'Pending')");
$stmt->bind_param("iiis", $user_id, $department_id, $reason_id, $booking_date);

if ($stmt->execute()) {
    echo "<script>alert('Booking submitted successfully!'); window.location.href = 'my_appointments.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>