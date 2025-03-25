<?php
session_start();
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user's ID

// Fetch user-specific bookings with user details
$sql = "SELECT bookings.*, 
               users.first_name, 
               users.last_name, 
               users.contact_number, 
               users.email_address, 
               departments.department_name, 
               reasons.reason_name 
        FROM bookings 
        INNER JOIN users ON bookings.user_id = users.user_id 
        LEFT JOIN departments ON bookings.department_id = departments.id 
        LEFT JOIN reasons ON bookings.reason_id = reasons.id 
        WHERE bookings.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Store bookings in an array
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-white">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <div class="space-x-4">
                <a href="homepage.php" class="text-white hover:text-blue-500">Home</a>
                <a href="announcement.php" class="text-white hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-white hover:text-blue-500">About</a>
                <a href="gallery.php" class="text-white hover:text-blue-500">Gallery</a>
                <a href="appointment_booking.php" class="text-white hover:text-blue-500">Book Appointment</a>
                <a href="my_appointments.php" class="text-white font-bold">My Appointments</a>
                <a href="profile.php" class="text-white hover:text-blue-500">Profile</a>
                <a href="logout.php" class="text-red-400 hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="flex-grow container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">My Appointments</h1>

        <!-- Table -->
        <div class="bg-white p-6 shadow-lg rounded-lg overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 border">First Name</th>
                        <th class="p-3 border">Last Name</th>
                        <th class="p-3 border">Contact Number</th>
                        <th class="p-3 border">Email Address</th>
                        <th class="p-3 border">Department</th>
                        <th class="p-3 border">Reason</th>
                        <th class="p-3 border">Booking Date</th>
                        <th class="p-3 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)) { ?>
                        <?php foreach ($bookings as $row) { ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-3 border"><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['email_address']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['department_name'] ?? ''); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['reason_name'] ?? ''); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                <td class="p-3 border">
                                    <span class="px-2 py-1 rounded 
                                        <?php echo ($row['status'] === 'Confirmed') ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500">No bookings found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer (Sticks to the bottom) -->
    <footer class="bg-blue-800 text-center py-4 shadow-md w-full">
        <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

</body>
</html>