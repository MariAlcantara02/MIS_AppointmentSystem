<?php
session_start();
$conn = new mysqli("localhost", "root", "", "appointment_booking");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email_address'];
    $role = $_POST['role']; // Role: admin or superadmin

    // Determine the correct table based on the role
    $table = ($role === "superadmin") ? "superadmins" : "admins";

    // Check if email exists
    $query = "SELECT id FROM $table WHERE email_address = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generate a secure random token
        $stmt->close();

        // Store the token in the database
        $query = "UPDATE $table SET reset_token=?, reset_expires=DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email_address=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        
        // Send email with reset link
        $reset_link = "http://yourwebsite.com/admin_reset_password.php?token=" . $token . "&role=" . $role;
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: $reset_link";
        $headers = "From: no-reply@yourwebsite.com\r\n";

        mail($email, $subject, $message, $headers);
        echo "Password reset link sent to your email!";
    } else {
        echo "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Admin Forgot Password</h2>
        <form method="post">
            <label>Email Address:</label>
            <input type="email" name="email_address" required class="block w-full p-2 border rounded mb-3">

            <label>Role:</label>
            <select name="role" required class="block w-full p-2 border rounded mb-3">
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
