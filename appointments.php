<?php
include 'connect.php';

$sql = "SELECT * FROM bookings"; // Replace with your actual query
$result = $conn->query($sql);

if ($result) {
    $bookings = $result;
} else {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-gray-800">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <div class="space-x-4">
                <a href="homepage.php" class="text-gray-700 hover:text-blue-500">Home</a>
                <a href="announcement.php" class="text-gray-700 hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-gray-700 hover:text-blue-500">About</a>
                <a href="gallery.php" class="text-gray-700 hover:text-blue-500">Gallery</a>
                <a href="appointment_booking.php" class="text-gray-700 hover:text-blue-500">Appointment</a>
                <a href="profile.php" class="text-gray-700 hover:text-blue-500">Profile</a>
                <a href="logout.php" class="text-gray-700 hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

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
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings && $bookings->num_rows > 0) { ?>
                        <?php while ($row = $bookings->fetch_assoc()) { ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-3 border"><?php echo $row['first_name']; ?></td>
                                <td class="p-3 border"><?php echo $row['last_name']; ?></td>
                                <td class="p-3 border"><?php echo $row['contact_number']; ?></td>
                                <td class="p-3 border"><?php echo $row['email_address']; ?></td>
                                <td class="p-3 border"><?php echo $row['department_name']; ?></td>
                                <td class="p-3 border"><?php echo $row['reason_name']; ?></td>
                                <td class="p-3 border"><?php echo $row['booking_date']; ?></td>
                                <td class="p-3 border">
                                    <span class="px-2 py-1 rounded 
                                        <?php echo ($row['status'] === 'Confirmed') ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td class="p-3 border space-x-2">
                                    <a href="approve_booking.php?id=<?php echo $row['id']; ?>" 
                                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition">
                                        Approve
                                    </a>
                                    <a href="reject_booking.php?id=<?php echo $row['id']; ?>" 
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                                        Reject
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">No bookings found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
