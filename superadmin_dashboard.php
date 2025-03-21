<?php 
session_start();
include 'connect.php';

if(isset($_POST['superadmin'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // 🔴 Use password_hash() in production

    // Fetch admin details including department_id
    $sql = "SELECT id, username FROM superadmins WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // ✅ Store admin details in SESSION
        $_SESSION['superadmin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username']; // Keeping this for reference

        // Debugging: Print session data after setting
        echo "<pre>✅ SESSION DATA AFTER LOGIN:\n";
        print_r($_SESSION);
        echo "</pre>";

        header("location: superadmin_dashboard.php");
        exit();
    } else {
        echo "❌ ERROR: Incorrect Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",
                        secondary: "#1e293b",
                    }
                }
            }
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-secondary text-white h-screen p-6 hidden md:block">
        <div class="text-center text-2xl font-bold mb-6">Menu</div>
        <ul class="space-y-4">
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-user-shield"></i>
                <span>Admin</span>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Navbar -->
        <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span class="text-lg font-semibold text-gray-800">San Pablo City Mega Capitol</span>
            </div>
            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <i class="fas fa-bars text-xl text-gray-700"></i>
            </button>
            <div class="hidden md:flex space-x-4">
                <a href="logout.php" class="text-gray-700 hover:text-red-500">Logout</a>
            </div>
        </nav>

       <!-- Mobile Sidebar -->
<div id="mobile-menu" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-secondary text-white w-64 p-6 h-full relative">
        <div class="text-center text-2xl font-bold mb-6">Menu</div>
        <ul class="space-y-4">
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-tachometer-alt"></i>
                <a href="superadmin_dashboard.php" class="text-white hover:text-blue-400">Dashboard</a>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-calendar-check"></i>
                <a href="appointments.php" class="text-white hover:text-blue-400">Appointments</a>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-users"></i>
                <a href="view_users.php" class="text-white hover:text-blue-400">Users</a>
            </li>
            <li class="flex items-center space-x-3 hover:bg-gray-700 p-2 rounded-md cursor-pointer">
                <i class="fas fa-user-shield"></i>
                <a href="view_admins.php" class="text-white hover:text-blue-400">Admin</a>
            </li>
        </ul>
        <button id="menu-close" class="absolute top-4 right-4 text-white text-2xl">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

        <!-- Main Content Area -->
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800">Welcome to the Super Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Manage your appointments, users, and more.</p>
        </div>
    </div>

    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("mobile-menu").classList.remove("hidden");
        });

        document.getElementById("menu-close").addEventListener("click", function() {
            document.getElementById("mobile-menu").classList.add("hidden");
        });
    </script>

</body>
</html>
