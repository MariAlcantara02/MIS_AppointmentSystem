<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$sql = "SELECT * FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="homepage.php" class="text-white text-lg font-semibold">San Pablo City Mega Capitol</a>
            <div class="space-x-4">
                <a href="homepage.php" class="text-white hover:underline">Home</a>
                <a href="announcement.php" class="text-white hover:underline">Announcement</a>
                <a href="about.php" class="text-white hover:underline">About</a>
                <a href="gallery.php" class="text-white hover:underline">Gallery</a>
                <a href="appointment_view.php" class="text-white font-bold">My Appointments</a>
                <a href="profile.php" class="text-white hover:underline">Profile</a>
                <a href="logout.php" class="text-red-400 hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">My Appointments</h1>

        <!-- Table -->
        <div class="bg-white p-6 shadow-lg rounded-lg overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 border">Department</th>
                        <th class="p-3 border">Reason</th>
                        <th class="p-3 border">Booking Date</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-3 border"><?php echo htmlspecialchars($row['department_name']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['reason_name']); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                <td class="p-3 border">
                                    <span class="px-2 py-1 rounded 
                                        <?php echo ($row['status'] === 'Confirmed') ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td class="p-3 border">
                                    <?php if ($row['status'] === 'Pending') { ?>
                                        <a href="cancel_booking.php?id=<?php echo $row['id']; ?>" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                                            Cancel
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-gray-500">No Action</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">No appointments found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-blue-800 text-center py-4 shadow-md mt-5">
        <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

</body>
</html>
