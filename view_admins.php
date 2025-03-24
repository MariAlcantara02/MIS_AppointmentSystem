<?php
session_start();
include 'connect.php';

// Fetch all admins with their department
$query = "SELECT admins.id, admins.first_name, admins.last_name, admins.email_address, admins.profile_picture, departments.department_name 
          FROM admins 
          JOIN departments ON admins.department_id = departments.id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admins</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="flex flex-col flex-1 min-h-screen">
        <!-- Navbar -->
        <nav class="bg-blue-800 shadow-md py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span class="text-lg font-semibold text-white">San Pablo City Mega Capitol</span>
            </div>
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <i class="fas fa-bars text-xl text-white"></i>
            </button>
            <div class="hidden md:flex space-x-4">
                <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
            </div>
        </nav>

        <!-- Admin Cards Section -->
        <div class="container mx-auto p-6 flex-grow">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">List of Admins</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while ($admin = $result->fetch_assoc()): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                            <img src="<?= !empty($admin['profile_picture']) ? 'uploads/' . $admin['profile_picture'] : 'https://via.placeholder.com/100' ?>" 
                                alt="Profile Picture" class="w-24 h-24 rounded-full mb-4">
                            <h3 class="text-lg font-semibold"><?php echo $admin['first_name'] . " " . $admin['last_name']; ?></h3>
                            <p class="text-gray-600 text-sm"><?php echo $admin['email_address']; ?></p>
                            <span class="mt-2 text-blue-700 font-medium"><?php echo $admin['department_name']; ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600 mt-4">No admins found.</p>
            <?php endif; ?>
        </div>

        <!-- Footer (Sticks to the Bottom) -->
        <footer class="bg-blue-800 text-center py-4 shadow-md flex-shrink-0">
            <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
        </footer>
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

<?php $conn->close(); ?>
