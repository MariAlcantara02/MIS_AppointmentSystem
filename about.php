<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-white">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <button class="md:hidden text-gray-600 focus:outline-none" id="menu-btn">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="hidden md:flex space-x-6" id="menu">
                <a href="homepage.php" class="text-white hover:text-blue-500">Home</a>
                <a href="announcement.php" class="text-white hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-white font-bold">About</a>
                <a href="gallery.php" class="text-white hover:text-blue-500">Gallery</a>
                <a href="appointment_booking.php" class="text-white hover:text-blue-500">Book Appointment</a>
                <a href="my_appointments.php" class="text-white hover:text-blue-500">My Appointments</a>
                <a href="profile.php" class="text-white hover:text-blue-500">Profile</a>
                <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="flex-grow">
        <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800">About</h1>
            <p class="text-gray-600 mt-2">
                Welcome to the San Pablo City Mega Capitol website. Our mission is to serve the community with efficiency and transparency.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-700">Our Vision</h2>
                    <p class="text-gray-600 mt-2">To be a leading government institution that fosters growth, sustainability, and community engagement.</p>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-700">Our Mission</h2>
                    <p class="text-gray-600 mt-2">Providing accessible, transparent, and efficient services to the citizens of San Pablo City.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (Stays at the Bottom) -->
    <footer class="bg-blue-800 text-center py-4 shadow-md w-full">
        <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Mobile Menu Toggle -->
    <script>
        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>

</body>
</html>
