<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming user_id is stored in session after login
    $user_id = $_SESSION['user_id'] ?? null; // Use null coalescing operator to handle undefined session key

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $department_id = $_POST['department_id'];
    $reason_id = $_POST['reason_id'];
    $booking_date = $_POST['booking_date'];

    if ($user_id !== null) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, first_name, last_name, contact_number, email_address, department_id, reason_id, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("issssiss", $user_id, $first_name, $last_name, $contact_number, $email_address, $department_id, $reason_id, $booking_date);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Booking submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: User ID is not set.";
    }
}

// Close the connection
$conn->close();
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
        <nav class="bg-blue-800 shadow-md py-4 px-6 flex justify-between items-center">
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

            <!-- Bookings Section -->
            <h2 class="text-xl font-bold text-gray-800 mt-6">Bookings</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="min-w-full bg-white mt-4">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Booking ID</th>
                            <th class="py-2 px-4 border-b">User ID</th>
                            <th class="py-2 px-4 border-b">Date</th>
                            <th class="py-2 px-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($booking = $result->fetch_assoc()): ?>
    <tr>
        <td class="py-2 px-4 border-b"><?php echo $booking['id']; ?></td>
        <td class="py-2 px-4 border-b"><?php echo $booking['user_id']; ?></td>
        <td class="py-2 px-4 border-b"><?php echo $booking['booking_date']; ?></td> <!-- Updated key -->
        <td class="py-2 px-4 border-b"><?php echo $booking['status']; ?></td>
    </tr>
<?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-600 mt-2">No bookings found.</p>
            <?php endif; ?>
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
