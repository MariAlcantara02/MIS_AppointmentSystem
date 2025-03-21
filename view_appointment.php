<?php
header('Content-Type: application/json'); // Set response type to JSON

$conn = new mysqli("localhost", "root", "", "appointment_booking");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch appointment details
    $query = "
        SELECT 
            b.id AS booking_id,
            b.first_name,
            b.last_name,
            b.email_address,
            b.contact_number,
            d.name AS department_name,
            r.name AS reason_name,
            u.id AS user_id,
            b.booking_date,
            b.status
        FROM bookings b
        JOIN departments d ON b.department_id = d.id
        JOIN reasons r ON b.reason_id = r.id
        JOIN users u ON b.user_id = u.id
        WHERE b.id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Return appointment details as JSON
    } else {
        echo json_encode(['error' => 'No appointment found with this ID']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Booking ID is required']);
}

$conn->close();
?>