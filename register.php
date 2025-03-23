<?php 
session_start(); // Start session before any output
include 'connect.php'; // Ensure database connection is available

// ✅ User Registration
if(isset($_POST['sign_up'])){
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $contact_number = trim($_POST['contact_number']);
    $email_address = trim($_POST['email_address']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Secure password hashing

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $check_email->bind_param("s", $email_address);
    $check_email->execute();
    $result = $check_email->get_result();

    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        // Insert new user
        $insertQuery = $conn->prepare("INSERT INTO users (first_name, last_name, contact_number, email_address, password) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssss", $first_name, $last_name, $contact_number, $email_address, $hashed_password);

        if($insertQuery->execute()){
            header("location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// ✅ User Login
if(isset($_POST["sign_in"])){
    $email_address = trim($_POST['email_address']);
    $password = trim($_POST['password']);

    $sql = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $sql->bind_param("s", $email_address);
    $sql->execute();
    $result = $sql->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){ // ✅ Correctly verifying password
            $_SESSION['email_address'] = $row['email_address'];
            $_SESSION['user_id'] = $row['user_id']; // Store user ID for reference
            header("location: homepage.php");
            exit();
        } else {
            echo "Incorrect Password!";
        }
    } else {
        echo "Incorrect Email or Password!";
    }
}

// ✅ Super Admin & Admin Login Handling
if(isset($_POST['register_admin'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // ✅ Hash admin password

    $insertAdmin = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $insertAdmin->bind_param("ss", $username, $hashed_password);

    if($insertAdmin->execute()){
        echo "Admin registered successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}


?>

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // ✅ Always start session at the top

include 'connect.php'; // ✅ Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // ✅ Role: 'admin' or 'super_admin'

    // Prevent SQL Injection
    if ($role === "admin") {
        $sql = "SELECT id, username, password, department_id FROM admins WHERE username = ?";
    } elseif ($role === "super_admin") {
        $sql = "SELECT id, username, password FROM super_admins WHERE username = ?";
    } else {
        echo "❌ Invalid role selected!";
        exit();
    }

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ✅ Secure password check using password_verify()
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $user['id'];

                if ($role === "admin") {
                    $_SESSION['department_id'] = $user['department_id']; // ✅ Admins have department_id
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: superadmin_dashboard.php");
                }
                exit();
            }
        }
    }
}
