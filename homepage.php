<?php 
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
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

    <!-- Greeting Section -->
    <div class="flex-grow flex items-center justify-center">
        <div class="text-center p-8 bg-white shadow-lg rounded-lg max-w-md">
            <p class="text-3xl font-bold text-gray-800">
                Hello, 
                <?php 
                if(isset($_SESSION['email_address'])){
                    $email_address= $_SESSION['email_address'];
                    $query=mysqli_query($conn,"SELECT first_name, last_name FROM users WHERE email_address='$email_address'");
                    if ($row = mysqli_fetch_assoc($query)) {
                        echo $row['first_name'].' '.$row['last_name'];
                    }
                } 
                ?> 😊
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 shadow-md">
        <p class="text-gray-600">&copy; 2023 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

</body>
</html>
