<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch the admin's department (optional: remove if not needed)
$admin_query = $conn->prepare("SELECT admins.department_id, departments.department_name 
                               FROM admins 
                               LEFT JOIN departments ON admins.department_id = departments.id 
                               WHERE admins.id = ?");

$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin_result = $admin_query->get_result();
$admin_row = $admin_result->fetch_assoc();
$admin_department_id = $admin_row['department_id'] ?? null;
$admin_department_name = $admin_row['department_name'] ?? 'Unknown Department';


// Fetch bookings filtered by department
$sql = "SELECT bookings.booking_id, 
               bookings.*, 
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
        WHERE bookings.department_id = ?
        ORDER BY bookings.booking_date DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_department_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Main Wrapper -->
    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-blue-800 text-white h-screen p-6 transition-all duration-300 md:block">
            <div class="flex items-center space-x-3 text-2xl font-bold mb-6">
                <button id="toggle-sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span id="menu-label" class="menu-text">Menu</span>
            </div>
            <ul class="space-y-4">
                <li class="hover:bg-gray-700 p-2 rounded-md">
                    <a href="admin_dashboard.php" class="flex items-center space-x-3">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="hover:bg-gray-700 p-2 rounded-md">
                    <a href="view_appointment_admin.php" class="flex items-center space-x-3">
                        <i class="fas fa-calendar-check"></i>
                        <span class="menu-text">Appointments</span>
                    </a>
                </li>
                <li class="hover:bg-gray-700 p-2 rounded-md">
                    <a href="admin_profile.php" class="flex items-center space-x-3">
                        <i class="fas fa-user-shield"></i>
                        <span class="menu-text">Admin Profile</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Content Wrapper -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <nav class="bg-blue-800 shadow-md py-4 px-6 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                    <span class="text-lg font-semibold text-white">San Pablo City Mega Capitol</span>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container mx-auto p-6 flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard - <?php echo htmlspecialchars($admin_department_name); ?></h1>

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
                            <?php if ($result->num_rows > 0) { ?>
                                <?php while ($row = $result->fetch_assoc()) { ?>
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
                                                <?php echo ($row['status'] === 'Confirmed') ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td class="p-3 border space-x-2">
                                            <?php
                                            if (!empty($row['booking_id'])) {
                                                echo '<div class="inline-flex space-x-2">
                                                <a href="approve_booking.php?id=' . htmlspecialchars($row['booking_id']) . '" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition"
                                                onclick="return confirm(\'Are you sure you want to approve this booking?\');">
                                                Approve
                                                </a>';

                                                echo '<a href="reject_booking.php?id=' . htmlspecialchars($row['booking_id']) . '" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition"
                                                onclick="return confirm(\'Are you sure you want to reject this booking?\');">
                                                Reject
                                                </a>';
                                            } else {
                                                echo "<span class='text-red-500'>Error: Booking ID missing!</span>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="9" class="p-4 text-center text-gray-500">No bookings found for your department.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer (Sticks to Bottom) -->
            <footer class="bg-blue-800 text-center py-4 shadow-md mt-auto">
                <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        document.getElementById("toggle-sidebar").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            const menuLabels = document.querySelectorAll(".menu-text");

            // Toggle sidebar width
            sidebar.classList.toggle("w-20");
            sidebar.classList.toggle("w-64");

            // Hide menu text when collapsed
            if (sidebar.classList.contains("w-20")) {
                menuLabels.forEach(label => label.classList.add("hidden"));
            } else {
                menuLabels.forEach(label => label.classList.remove("hidden"));
            }
        });
    </script>
</body>
</html>
