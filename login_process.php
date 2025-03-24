<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if login role is specified
    $role = $_POST['role'] ?? '';

    // Prepared statement to prevent SQL injection
    if ($role === 'super_admin') {
        $stmt = $conn->prepare("SELECT * FROM super_admins WHERE username = ? AND password = ?");
    } elseif ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    } else {
        echo "Invalid role!";
        exit();
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['username'] = $user['username'];

        if ($role === 'super_admin') {
            $_SESSION['role'] = 'super_admin';
            $_SESSION['super_admin_id'] = $user['id'];  // Storing ID for session validation
            header("Location: superadmin_dashboard.php");
        } elseif ($role === 'admin') {
            $_SESSION['role'] = 'admin';
            $_SESSION['admin_id'] = $user['id'];  // ✅ Now available for admin_dashboard.php
            $_SESSION['department_id'] = $user['department_id']; 
            header("Location: admin_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid username or password!";
    }

    $stmt->close();
}
?>
