<?php
session_start();
include 'connect.php';

// Fetch users from the database, including profile_picture
$result = $conn->query("SELECT first_name, last_name, email_address, profile_picture FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
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

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1">
            <!-- Navbar -->
            <nav class="bg-blue-800 shadow-md py-4 px-6 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                    <span class="text-lg font-semibold text-white">San Pablo City Mega Capitol</span>
                </div>
                <!-- Mobile Menu Button -->
                <button id="menu-toggle" class="md:hidden focus:outline-none">
                    <i class="fas fa-bars text-xl text-white"></i>
                </button>
                <div class="hidden md:flex space-x-4">
                    <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
                </div>
            </nav>

            <!-- Users Cards Section -->
            <div class="container mx-auto p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">List of Users</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                            <?php
                            // Check if user has a profile picture
                            $profile_picture = !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'default_profile.png';
                            ?>
                            <img src="<?= $user['profile_picture'] ?: 'https://via.placeholder.com/150' ?>" alt="Profile Picture" class="w-24 h-24 rounded-full mb-4 object-cover">
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h3>
                            <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($user['email_address']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600 mt-4">No users found.</p>
            <?php endif; ?>
        </div>

            <!-- Footer (now inside main content, sticks to bottom) -->
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
