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
    <nav class="bg-white shadow-md py-4">
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
                <div>
                    <label for="first_name" class="block text-gray-700 font-medium mb-1">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label for="last_name" class="block text-gray-700 font-medium mb-1">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label for="contact_number" class="block text-gray-700 font-medium mb-1">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label for="email_address" class="block text-gray-700 font-medium mb-1">Email Address</label>
                    <input type="email" id="email_address" name="email_address" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label for="department" class="block text-gray-700 font-medium mb-1">Department</label>
                    <select id="department" name="department_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "appointment_booking");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $result = $conn->query("SELECT * FROM departments");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['department_name']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div>
                    <label for="reason" class="block text-gray-700 font-medium mb-1">Reason</label>
                    <select id="reason" name="reason_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "appointment_booking");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $result = $conn->query("SELECT * FROM reasons");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['reason_name']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
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
