<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "appointment_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("You must be logged in to view this page.");
}

// Fetch user data
$query = "SELECT first_name, last_name, email_address, contact_number, profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email_address = $_POST['email_address'];
    $contact_number = $_POST['contact_number'];
    
    // Check if an image file is uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        
        // Ensure the `uploads/` directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $new_file_name = "profile_" . $user_id . "." . $file_extension; // Unique file name
        $target_file = $target_dir . $new_file_name;
        
        // Validate the file type (optional but recommended)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update user profile with image
            $query = "UPDATE users SET first_name=?, last_name=?, email_address=?, contact_number=?, profile_picture=? WHERE user_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $first_name, $last_name, $email_address, $contact_number, $target_file, $user_id);
        } else {
            die("Error uploading file.");
        }
    } else {
        // Update without profile picture
        $query = "UPDATE users SET first_name=?, last_name=?, email_address=?, contact_number=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email_address, $contact_number, $user_id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: profile.php"); // Refresh profile page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-white">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <div class="space-x-4">
            <a href="homepage.php" class="text-white hover:text-blue-500">Home</a>
                <a href="announcement.php" class="text-white hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-white hover:text-blue-500">About</a>
                <a href="gallery.php" class="text-white hover:text-blue-500">Gallery</a>
                <a href="appointment_booking.php" class="text-white hover:text-blue-500">Book Appointment</a>
                <a href="my_appointments.php" class="text-white hover:text-blue-500">My Appointments</a>
                <a href="profile.php" class="text-white font-bold">Profile</a>
                <a href="logout.php" class="text-white hover:text-red-500">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
        <form method="POST" enctype="multipart/form-data" class="space-y-4 mt-6">
            <div class="flex flex-col md:flex-row items-center bg-gray-50 p-6 rounded-lg shadow-md">
                <img src="<?= $user['profile_picture'] ?: 'https://via.placeholder.com/150' ?>" alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-gray-300">
                <div class="ml-6 w-full">
                    <label>Profile Picture:</label>
                    <input type="file" name="profile_picture" class="block w-full p-2 border rounded">
                </div>
            </div>
            <div>
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" class="block w-full p-2 border rounded">
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" class="block w-full p-2 border rounded">
            </div>
            <div>
                <label>Email Address:</label>
                <input type="email" name="email_address" value="<?= htmlspecialchars($user['email_address']) ?>" class="block w-full p-2 border rounded">
            </div>
            <div>
                <label>Contact Number:</label>
                <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>" class="block w-full p-2 border rounded">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Profile</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-800 text-center py-4 shadow-md mt-5">
        <p class="text-white">&copy; 2025 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

</body>
</html>
