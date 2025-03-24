<?php
session_start();
include 'connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch current admin details
$query = "SELECT first_name, last_name, email_address, profile_picture FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL);

    // Handle profile picture upload
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $file_name;
            } else {
                $error = "Error uploading file.";
            }
        } else {
            $error = "Only JPG, JPEG, and PNG files are allowed.";
        }
    } else {
        $profile_picture = $admin['profile_picture']; // Keep the old picture
    }

    // If no errors, update the database
    if (!isset($error)) {
        $update_query = "UPDATE admins SET first_name = ?, last_name = ?, email_address = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $profile_picture, $admin_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            header("Refresh:2; url=admin_profile.php"); // Auto-refresh
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>

<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-blue-800 text-white h-screen p-6 fixed left-0 top-0">
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
                <a href="admin_profile.php" class="flex items-center space-x-3">
                    <i class="fas fa-user-shield"></i>
                    <span class="menu-text">Admin Profile</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 ml-64">
        <!-- Navbar -->
        <nav class="bg-blue-800 shadow-md py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span class="text-lg font-semibold text-white">San Pablo City Mega Capitol</span>
            </div>
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <i class="fas fa-bars text-xl text-white"></i>
            </button>
            <div class="hidden md:flex space-x-4">
                <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
            </div>
        </nav>

        <!-- Profile Content (Centered) -->
        <div class="container mx-auto p-6 flex-grow">
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4 text-center">Admin Profile</h2>

                <?php if (isset($success)): ?>
                    <p class="text-green-600 text-sm mb-2"><?= $success ?></p>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <p class="text-red-600 text-sm mb-2"><?= $error ?></p>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="flex flex-col items-center mb-4">
                        <img src="<?= !empty($admin['profile_picture']) ? 'uploads/' . $admin['profile_picture'] : 'https://via.placeholder.com/150' ?>" 
                            alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mb-2">
                        <input type="file" name="profile_picture" class="text-sm">
                    </div>

                    <!-- First Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700">First Name:</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($admin['first_name']) ?>" 
                            class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Last Name:</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($admin['last_name']) ?>" 
                            class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Email Address:</label>
                        <input type="email" name="email_address" value="<?= htmlspecialchars($admin['email_address']) ?>" 
                            class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">
                        Update Profile
                    </button>
                </form>

                <!-- Back Button -->
                <a href="admin_dashboard.php" class="block text-center text-blue-500 mt-4">Back to Dashboard</a>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <footer class="bg-blue-800 text-center py-4 shadow-md">
            <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById("toggle-sidebar").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            const menuLabels = document.querySelectorAll(".menu-text");

            // Toggle sidebar width
            sidebar.classList.toggle("w-20");
            sidebar.classList.toggle("w-64");

            // Hide menu text when collapsed
            if (sidebar.classList.contains("w-20")) {
                menuLabels.forEach(label => label.classList.add("hidden"));
            } else {
                menuLabels.forEach(label => label.classList.remove("hidden"));
            }
        });
    </script>
</body>
</html>

