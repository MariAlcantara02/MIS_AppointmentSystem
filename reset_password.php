<?php
session_start();
$conn = new mysqli("localhost", "root", "", "appointment_booking");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token is valid and not expired
    $query = "SELECT user_id FROM users WHERE reset_token=? AND reset_expires > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            
            // Update the password in the database
            $query = "UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();
            
            echo "Password has been reset! You can now <a href='index.php'>log in</a>.";
        }
    } else {
        echo "Invalid or expired token!";
        exit;
    }
} else {
    echo "No token provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Reset Password</h2>
        <form method="post">
            <label>New Password:</label>
            <input type="password" name="password" required class="block w-full p-2 border rounded mb-3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Reset Password</button>
        </form>
    </div>
</body>
</html>
