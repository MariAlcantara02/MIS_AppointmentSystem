<?php
session_start();
include 'connect.php';

if(isset($_POST['admin'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // 🔴 Use password_hash() in production

    // Fetch admin details including department_id
    $sql = "SELECT id, username, department_id, email_address FROM admins WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // ✅ Store admin details in SESSION
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['department_id'] = $admin['department_id']; // 🔴 This was missing before!
        $_SESSION['username'] = $admin['username']; // Keeping this for reference

        // Debugging: Print session data after setting
        echo "<pre>✅ SESSION DATA AFTER LOGIN:\n";
        print_r($_SESSION);
        echo "</pre>";

        header("location: admin_dashboard.php");
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
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-gray-800">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <div class="space-x-4">
                <a href="logout.php" class="text-gray-700 hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

        <!-- Table -->
        <div class="bg-white p-6 shadow-lg rounded-lg overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 border">First Name</th>
                        <th class="p-3 border">Last Name</th>
                        <th class="p-3 border">Contact Number</th>
                        <th class="p-3 border">Email Address</th>
                        <th class="p-3 border">Department</th>
                        <th class="p-3 border">Reason</th>
                        <th class="p-3 border">Booking Date</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($bookings)) { ?>
        <?php foreach ($bookings as $row) { ?>
            <tr class="border-b hover:bg-gray-100">
                <td class="p-3 border"><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td class="p-3 border"><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td class="p-3 border"><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td class="p-3 border"><?php echo htmlspecialchars($row['email_address']); ?></td>
                <td class="p-3 border"><?php echo htmlspecialchars($row['department_name']); ?></td>  <!-- Fixed -->
                <td class="p-3 border"><?php echo htmlspecialchars($row['reason_name']); ?></td>  <!-- Fixed -->
                <td class="p-3 border"><?php echo htmlspecialchars($row['booking_date']); ?></td>
                <td class="p-3 border">
                    <span class="px-2 py-1 rounded 
                        <?php echo ($row['status'] === 'Confirmed') ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </td>
                <td class="p-3 border space-x-2">
                    <a href="approve_booking.php?id=<?php echo $row['id']; ?>" 
                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition">
                        Approve
                    </a>
                    <a href="reject_booking.php?id=<?php echo $row['id']; ?>" 
                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                        Reject
                    </a>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="9" class="p-4 text-center text-gray-500">No bookings found.</td>
        </tr>
    <?php } ?>
</tbody>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
            </table>
        </div>
    </div>

</body>
</html>
