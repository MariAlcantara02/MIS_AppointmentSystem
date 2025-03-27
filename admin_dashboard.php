<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch Admin Department
$admin_query = $conn->prepare("
    SELECT admins.department_id, departments.department_name 
    FROM admins 
    LEFT JOIN departments ON admins.department_id = departments.id 
    WHERE admins.id = ?
");
$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin_result = $admin_query->get_result();
$admin_row = $admin_result->fetch_assoc();
$admin_department_id = $admin_row['department_id'] ?? null;
$admin_department_name = $admin_row['department_name'] ?? 'Unknown Department';

// Total Appointments
$total_appointments_query = $conn->prepare("
    SELECT COUNT(*) AS total FROM bookings WHERE department_id = ?
");
$total_appointments_query->bind_param("i", $admin_department_id);
$total_appointments_query->execute();
$total_appointments = $total_appointments_query->get_result()->fetch_assoc()['total'] ?? 0;

// Fetch analytics data for the admin's department
$total_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE department_id = $admin_department_id")->fetch_assoc()['count'];
$confirmed_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status='Confirmed' AND department_id = $admin_department_id")->fetch_assoc()['count'];
$pending_appointments = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status='Pending' AND department_id = $admin_department_id")->fetch_assoc()['count'];

// Appointment Status Breakdown
$status_query = $conn->prepare("
    SELECT status, COUNT(*) AS count 
    FROM bookings 
    WHERE department_id = ?
    GROUP BY status
");
$status_query->bind_param("i", $admin_department_id);
$status_query->execute();
$status_result = $status_query->get_result();
$status_counts = ['Confirmed' => 0, 'Pending' => 0, 'Rejected' => 0];

while ($row = $status_result->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}

// Most Common Appointment Reasons
$reasons_query = $conn->prepare("
    SELECT reasons.reason_name, COUNT(bookings.reason_id) AS count 
    FROM bookings 
    LEFT JOIN reasons ON bookings.reason_id = reasons.id 
    WHERE bookings.department_id = ?
    GROUP BY bookings.reason_id 
    ORDER BY count DESC
");
$reasons_query->bind_param("i", $admin_department_id);
$reasons_query->execute();
$reasons_result = $reasons_query->get_result();
$reasons_data = [];

while ($row = $reasons_result->fetch_assoc()) {
    $reasons_data[] = $row;
}

// Appointments Over Last 7 Days
$trend_query = $conn->prepare("
    SELECT DATE(booking_date) AS date, COUNT(*) AS count 
    FROM bookings 
    WHERE department_id = ? AND booking_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(booking_date)
");
$trend_query->bind_param("i", $admin_department_id);
$trend_query->execute();
$trend_result = $trend_query->get_result();
$appointment_trends = [];

while ($row = $trend_result->fetch_assoc()) {
    $appointment_trends[$row['date']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="create_announcement_admin.php" class="flex items-center space-x-3">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="menu-text">Announcements</span>
                </a>
            </li>
            <li class="hover:bg-gray-700 p-2 rounded-md">
                <a href="admin_profile.php" class="flex items-center space-x-3">
                    <i class="fas fa-user-shield"></i>
                    <span class="menu-text">Profile</span>
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

        <!-- Main Content -->
        <div class="container mx-auto p-6 flex-grow overflow-y-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard - <?php echo htmlspecialchars($admin_department_name); ?></h1>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 shadow-lg rounded-lg text-center">
                    <i class="fas fa-calendar-check text-green-600 text-4xl"></i>
                    <h3 class="text-lg font-bold">Total Appointments</h3>
                    <p class="text-3xl font-semibold text-black"><?php echo $total_appointments; ?></p>
                </div>
                <div class="bg-white p-6 shadow-lg rounded-lg text-center">
                    <i class="fas fa-check-circle text-blue-600 text-4xl"></i>
                    <h3 class="text-lg font-bold">Confirmed</h3>
                    <p class="text-3xl font-semibold text-black"><?php echo $confirmed_appointments; ?></p>
                </div>
                <div class="bg-white p-6 shadow-lg rounded-lg text-center">
                    <i class="fas fa-clock text-yellow-600 text-4xl"></i>
                    <h3 class="text-lg font-bold">Pending</h3>
                    <p class="text-3xl font-semibold text-black"><?php echo $pending_appointments; ?></p>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <!-- Appointment Status Breakdown Chart -->
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Appointment Status Breakdown</h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <!-- Appointments Over Time Chart -->
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Appointments Over Last 7 Days</h3>
                        <canvas id="trendChart"></canvas>
                </div>

                <!-- Most Common Appointment Reasons -->
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Most Common Reasons</h3>
                        <ul class="space-y-2">
                            <?php foreach ($reasons_data as $reason) { ?>
                                <li class="flex justify-between">
                                <span><?php echo htmlspecialchars($reason['reason_name']); ?></span>
                                <span class="font-semibold"><?php echo $reason['count']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                </div>

            </div>


        </div>

        <!-- Footer (Now Sticks to Bottom) -->
        <footer class="bg-blue-800 text-center text-white py-4 w-full mt-auto">
            &copy; 2025 San Pablo City Mega Capitol. All rights reserved.
        </footer>

    </div>

<!-- Charts -->
<script>
    // Downsized Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Pending', 'Rejected'],
            datasets: [{
                data: [<?php echo $status_counts['Confirmed']; ?>, <?php echo $status_counts['Pending']; ?>, <?php echo $status_counts['Rejected']; ?>],
                backgroundColor: ['#16a34a', '#eab308', '#dc2626'],
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            width: 250,
            height: 250
        }
    });

    // Downsized Line Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [<?php foreach ($appointment_trends as $date => $count) { echo "'$date', "; } ?>],
            datasets: [{
                label: 'Appointments',
                data: [<?php foreach ($appointment_trends as $count) { echo "$count, "; } ?>],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            width: 300,
            height: 200
        }
    });
</script>
<!-- Sidebar Toggle Script -->
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


