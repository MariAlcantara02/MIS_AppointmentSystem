<?php
include 'connect.php';

// Fetch all announcements from the database
$announcement_query = $conn->prepare("
    SELECT a.title, a.content, a.created_at, a.banner_photo, d.department_name 
    FROM announcements a
    LEFT JOIN departments d ON a.department_id = d.id
    ORDER BY a.created_at DESC
");
$announcement_query->execute();
$announcements = $announcement_query->get_result();
?>

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
                <a href="homepage.php" class="text-white hover:text-blue-500">Home</a>
                <a href="announcement.php" class="text-white hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-white hover:text-blue-500">About</a>
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
            <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
            <p class="text-gray-600 mt-2">Stay updated with the latest announcements and events.</p>

            <!-- Dynamic Announcements -->
            <div class="mt-6 space-y-6">
                <?php if ($announcements->num_rows > 0): ?>
                    <?php while ($row = $announcements->fetch_assoc()): ?>
                        <div class="bg-gray-50 border-l-4 border-blue-500 rounded-lg shadow-md">
                            <!-- Display Banner Image If Exists -->
                            <?php if (!empty($row['banner_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($row['banner_photo']); ?>" alt="Banner Image" class="w-full h-64 object-cover rounded-t-lg">
                            <?php endif; ?>

                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($row['title']); ?></h2>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($row['content']); ?></p>
                                <p class="text-gray-500 text-xs mt-2">Posted by <?php echo htmlspecialchars($row['department_name']); ?> - <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-600">No announcements available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
