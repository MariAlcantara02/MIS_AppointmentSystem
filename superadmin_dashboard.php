<?php
session_start();
include 'connect.php';

// Fetch analytics data
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
$completed_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status='Completed'")->fetch_assoc()['count'];
$pending_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status='Pending'")->fetch_assoc()['count'];
$total_admins = $conn->query("SELECT COUNT(*) AS count FROM admins")->fetch_assoc()['count'];

// Get department distribution for chart
$department_query = $conn->query("SELECT departments.department_name, COUNT(admins.id) AS count FROM admins 
                                  JOIN departments ON admins.department_id = departments.id 
                                  GROUP BY departments.department_name");

$departments = [];
$department_counts = [];

while ($row = $department_query->fetch_assoc()) {
    $departments[] = $row['department_name'];
    $department_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .small-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex h-screen">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-blue-800 text-white h-screen p-6 transition-all duration-300">
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
                    <i class="fa-solid fa-user-tie"></i>
                    <span class="menu-text">Admin</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div id="main-content" class="flex flex-col flex-1 min-h-screen overflow-x-auto">

        <!-- Navbar -->
        <nav class="bg-blue-800 py-4 px-6 flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-3">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span class="text-lg font-semibold text-white">San Pablo City Mega Capitol</span>
            </div>
            <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
        </nav>

        <div class="container mx-auto p-6 flex-grow overflow-y-auto">
            <h1 class="text-2xl font-bold text-gray-800">Welcome to the Super Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Manage your appointments, users, and more.</p>

            <!-- Dashboard Analytics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                    <i class="fas fa-users text-blue-600 text-4xl"></i>
                    <h3 class="text-lg font-semibold mt-2">Total Users</h3>
                    <p class="text-2xl font-bold"><?php echo $total_users; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                    <i class="fa-solid fa-user-tie text-purple-600 text-4xl"></i>
                    <h3 class="text-lg font-semibold mt-2">Total Admins</h3>
                    <p class="text-2xl font-bold"><?php echo $total_admins; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                    <i class="fas fa-calendar-check text-green-600 text-4xl"></i>
                    <h3 class="text-lg font-semibold mt-2">Total Appointments</h3>
                    <p class="text-2xl font-bold"><?php echo $total_appointments; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                    <i class="fas fa-check-circle text-blue-600 text-4xl"></i>
                    <h3 class="text-lg font-semibold mt-2">Completed Appointments</h3>
                    <p class="text-2xl font-bold"><?php echo $completed_appointments; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center">
                <i class="fas fa-clock text-yellow-600 text-4xl"></i>
                    <h3 class="text-lg font-semibold mt-2">Pending Appointments</h3>
                    <p class="text-2xl font-bold"><?php echo $pending_appointments; ?></p>
                </div>
            </div>

            <!-- Department Chart -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Users per Department</h3>
                <canvas id="departmentChart"></canvas>
            </div>
        </div>

        <!-- Footer (Now Sticks to Bottom) -->
        <footer class="bg-blue-800 text-center text-white py-4 w-full mt-auto">
            &copy; 2025 San Pablo City Mega Capitol. All rights reserved.
        </footer>

    </div>

    <!-- Charts -->
    <!-- Charts -->
    <script>
    const ctx = document.getElementById('departmentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($departments); ?>,
            datasets: [{
                label: 'Users per Department',
                data: <?php echo json_encode($department_counts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });
    </script>
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
