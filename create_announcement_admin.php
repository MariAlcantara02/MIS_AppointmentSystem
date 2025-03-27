<?php
session_start();
include 'connect.php';

// Ensure admin is logged in
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

$success_message = $error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $banner_photo = null; // Default to null if no image is uploaded

    // Validate inputs
    if (empty($title) || empty($content)) {
        $error_message = "All fields are required.";
    } else {
        // Handle file upload
        if (!empty($_FILES['banner_photo']['name'])) {
            $target_dir = "uploads/"; // Folder where images will be stored
            $file_name = basename($_FILES['banner_photo']['name']);
            $file_path = $target_dir . uniqid() . "_" . $file_name; // Generate unique file name
            $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

            // Allowed file types
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES["banner_photo"]["tmp_name"], $file_path)) {
                    $banner_photo = $file_path; // Store file path for database
                } else {
                    $error_message = "Error uploading file.";
                }
            } else {
                $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }

        // Insert into database if no error
        if (empty($error_message)) {
            $stmt = $conn->prepare("INSERT INTO announcements (department_id, admin_id, title, content, banner_photo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $admin_department_id, $admin_id, $title, $content, $banner_photo);

            if ($stmt->execute()) {
                $success_message = "Announcement posted successfully!";
            } else {
                $error_message = "Error posting announcement.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Announcements for <?php echo htmlspecialchars($admin_department_name); ?></h1>

        <?php if (!empty($error_message)): ?>
            <p class="text-red-500 bg-red-100 p-2 rounded"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="text-green-500 bg-green-100 p-2 rounded"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="create_announcement_admin.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-lg rounded-lg">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Title:</label>
                <input type="text" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Content:</label>
                <textarea name="content" rows="5" class="w-full p-2 border border-gray-300 rounded" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Banner Image:</label>
                <input type="file" name="banner_photo" accept="image/*" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Post Announcement</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-800 text-center text-white py-4 w-full mt-auto">
        &copy; 2025 San Pablo City Mega Capitol. All rights reserved.
    </footer>

</div>
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
