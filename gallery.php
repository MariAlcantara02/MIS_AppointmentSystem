<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-gray-800">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <button class="md:hidden text-gray-600 focus:outline-none" id="menu-btn">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="hidden md:flex space-x-6" id="menu">
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

    <!-- Gallery Section -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800">Gallery</h1>
        <p class="text-gray-600 mt-2">Explore our collection of images showcasing events, landmarks, and community activities.</p>

        <!-- Image Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
            <img src="image1.jpg" alt="Gallery Image 1" class="w-full h-48 object-cover rounded-lg shadow-md">
            <img src="image2.jpg" alt="Gallery Image 2" class="w-full h-48 object-cover rounded-lg shadow-md">
            <img src="image3.jpg" alt="Gallery Image 3" class="w-full h-48 object-cover rounded-lg shadow-md">
            <img src="image4.jpg" alt="Gallery Image 4" class="w-full h-48 object-cover rounded-lg shadow-md">
            <img src="image5.jpg" alt="Gallery Image 5" class="w-full h-48 object-cover rounded-lg shadow-md">
            <img src="image6.jpg" alt="Gallery Image 6" class="w-full h-48 object-cover rounded-lg shadow-md">
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 shadow-md mt-5">
        <p class="text-gray-600">&copy; 2023 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Mobile Menu Toggle -->
    <script>
        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>

</body>
</html>
