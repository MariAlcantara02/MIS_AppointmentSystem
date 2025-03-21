<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php

$host="localhost";
$user="root";
$pass="";
$db= "appointment_booking";
$conn=new mysqli($host,$user,$pass,$db);
if ($conn->connect_error) {
    echo "Failed to connect DB".$conn->connect_error;
}
$sql = "SELECT 
            bookings.*, 
            COALESCE(departments.department_name, 'Unknown Department') AS department_name, 
            COALESCE(reasons.reason_name, 'Unknown Reason') AS reason_name
        FROM bookings
        LEFT JOIN departments ON bookings.department_id = departments.id
        LEFT JOIN reasons ON bookings.reason_id = reasons.id";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error); // Debugging
}

?>