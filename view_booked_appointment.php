<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming user_id is stored in session after login
    $user_id = $_SESSION['user_id'] ?? null; // Use null coalescing operator to handle undefined session key

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $department_id = $_POST['department_id'];
    $reason_id = $_POST['reason_id'];
    $booking_date = $_POST['booking_date'];

    if ($user_id !== null) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, first_name, last_name, contact_number, email_address, department_id, reason_id, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("issssiss", $user_id, $first_name, $last_name, $contact_number, $email_address, $department_id, $reason_id, $booking_date);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Booking submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: User ID is not set.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",
                        secondary: "#1e293b",
                    }
                }
            }
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

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
            <a href="superadmin_dashboard.php" class="flex items-center space-x-3">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md">
            <a href="view_booked_appointment.php" class="flex items-center space-x-3">
                <i class="fas fa-calendar-check"></i>
                <span class="menu-text">Appointments</span>
            </a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md">
            <a href="view_users.php" class="flex items-center space-x-3">
                <i class="fas fa-users"></i>
                <span class="menu-text">Users</span>
            </a>
        </li>
        <li class="hover:bg-gray-700 p-2 rounded-md">
            <a href="view_admins.php" class="flex items-center space-x-3">
                <i class="fas fa-user-shield"></i>
                <span class="menu-text">Admin</span>
            </a>
        </li>
    </ul>
</aside>

<!-- Main Content -->
<div class="flex-1 flex flex-col min-h-screen">

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

    <!-- Main Content Area -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-gray-800">Booked Appointments</h1>

        <!-- Bookings Section -->
        <h2 class="text-xl font-bold text-gray-800 mt-6">Bookings</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white mt-4">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Booking ID</th>
                        <th class="py-2 px-4 border-b">User ID</th>
                        <th class="py-2 px-4 border-b">Date</th>
                        <th class="py-2 px-4 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $booking['booking_id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $booking['user_id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $booking['booking_date']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $booking['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-600 mt-2">No bookings found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer (Now sticks to the bottom) -->
    <footer class="bg-blue-800 text-white text-center py-4">
        &copy; 2025 San Pablo City Mega Capitol. All rights reserved.
    </footer>

</div>

<script>
    document.getElementById("toggle-sidebar").addEventListener("click", function() {
        const sidebar = document.getElementById("sidebar");
        const menuLabels = document.querySelectorAll(".menu-text");

        sidebar.classList.toggle("w-20");
        sidebar.classList.toggle("w-64");

        if (sidebar.classList.contains("w-20")) {
            menuLabels.forEach(label => label.classList.add("hidden"));
        } else {
            menuLabels.forEach(label => label.classList.remove("hidden"));
        }
    });
</script>

</body>
</html>

