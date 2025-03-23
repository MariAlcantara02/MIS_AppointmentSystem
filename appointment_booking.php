<?php
session_start(); // Start session to get logged-in user ID
$conn = new mysqli("localhost", "root", "", "appointment_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's ID
$user_id = $_SESSION['user_id'] ?? null; // Ensure user is logged in

if (!$user_id) {
    die("You must be logged in to book an appointment.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

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
                <a href="appointment_booking.php" class="text-gray-700 hover:text-blue-500">Book Appointment</a>
                <a href="my_appointments.php" class="text-gray-700 hover:text-blue-500">My Appointments</a>
                <a href="profile.php" class="text-gray-700 hover:text-blue-500">Profile</a>
                <a href="logout.php" class="text-gray-700 hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Booking Form -->
    <div class="flex-grow flex items-center justify-center px-4">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Book an Appointment</h1>
            <form action="submit_booking.php" method="POST" class="space-y-4">
                
                <!-- Department -->
<div>
    <label for="department" class="block text-gray-700 font-medium mb-1">Department</label>
    <select id="department" name="department_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        <option value="" disabled selected>Select Department</option>
        <?php
        $result = $conn->query("SELECT id, department_name FROM departments"); // Changed column names
        if (!$result) {
            die("Error fetching departments: " . $conn->error);
        }
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['department_name']}</option>";
        }
        ?>
    </select>
</div>

<!-- Reason -->
<div>
    <label for="reason" class="block text-gray-700 font-medium mb-1">Reason</label>
    <select id="reason" name="reason_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        <option value="" disabled selected>Select Reason</option>
        <?php
        $result = $conn->query("SELECT id, reason_name FROM reasons"); // Changed column names
        if (!$result) {
            die("Error fetching reasons: " . $conn->error);
        }
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['reason_name']}</option>";
        }
        ?>
    </select>
</div>


                <!-- Booking Date -->
                <div>
                    <label for="booking_date" class="block text-gray-700 font-medium mb-1">Booking Date</label>
                    <input type="date" id="booking_date" name="booking_date" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">Submit</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 shadow-md">
        <p class="text-gray-600">&copy; 2023 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

</body>
</html>
