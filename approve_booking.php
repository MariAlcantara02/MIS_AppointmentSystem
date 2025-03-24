<?php
session_start();
include 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking ID is provided
if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Prepare the SQL query to update booking status
    $update_query = $conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE id = ?");
    $update_query->bind_param("i", $booking_id);

    if ($update_query->execute()) {
        $_SESSION['success_message'] = "Appointment approved successfully.";
    } else {
        $_SESSION['error_message'] = "Error approving appointment.";
    }

    $update_query->close();
} else {
    $_SESSION['error_message'] = "Invalid booking ID.";
}

// Redirect back to the admin appointments page
header("Location: view_appointment_admin.php");
exit();
?>
