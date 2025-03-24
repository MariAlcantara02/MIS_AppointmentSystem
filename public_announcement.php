<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
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
            <a href="public_announcement.php" class="text-white font-bold">Announcement</a>
                <a href="public_about.php" class="text-white hover:text-blue-500">About</a>
                <a href="public_gallery.php" class="text-white hover:text-blue-500">Gallery</a>
                <a href="index.php" class="text-white hover:text-blue-500">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Announcement Section -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
        <p class="text-gray-600 mt-2">Stay updated with the latest announcements and events.</p>

        <!-- Example Announcement -->
        <div class="mt-6 space-y-4">
            <div class="p-4 bg-gray-50 border-l-4 border-blue-500 rounded-lg">
                <h2 class="text-lg font-semibold text-gray-700">Upcoming Event</h2>
                <p class="text-gray-600 text-sm">Join us for the Mega Capitol Annual Summit on April 15, 2025.</p>
            </div>

            <div class="p-4 bg-gray-50 border-l-4 border-green-500 rounded-lg">
                <h2 class="text-lg font-semibold text-gray-700">New Services Available</h2>
                <p class="text-gray-600 text-sm">We have introduced online appointment booking for faster service.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-800 text-center py-4 shadow-md mt-5">
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