<?php 

include 'connect.php';

if(isset($_POST['sign_up'])){
    $first_name=($_POST['first_name']);
    $last_name=($_POST['last_name']);
    $email_address=($_POST['email_address']);
    $password=($_POST['password']);
    $password=md5($password);

        $check_email="SELECT * From users where email_address='$email_address'";
        $result=$conn->query($check_email);
        if($result->num_rows > 0){
            echo "Email Address Already Exists! ";
        }
        else{
            $insertQuery="INSERT INTO users(first_name,last_name,email_address,password)
            VALUES ('$first_name','$last_name','$email_address','$password')";
            if($conn->query($insertQuery)==TRUE){
                header("location: index.php");
            }
            else{
                echo "Error:".$conn->error;
            }
        }
}

if(isset($_POST["sign_in"])){
    $email_address=$_POST['email_address'];
    $password=$_POST['password'];
    $password=md5($password);

    $sql="SELECT * FROM users WHERE email_address='$email_address' and password='$password'";
    $result=$conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row= $result->fetch_assoc();
        $_SESSION['email_address']=$row['email_address'];
        header("location: homepage.php");
        exit();
    }
    else{
        echo "Incorret Email or Password";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if super admin
    $super_admin = $conn->query("SELECT * FROM super_admins WHERE username='$username' AND password='$password'");
    if ($super_admin->num_rows > 0) {
        $_SESSION['role'] = 'super_admin';
        header("Location: superadmin_dashboard.php");
        exit();
    }

    // Check if admin
    $admin = $conn->query("SELECT * FROM admins WHERE username='$username' AND password='$password'");
    if ($admin->num_rows > 0) {
        $_SESSION['role'] = 'admin';
        $_SESSION['department_id'] = $admin->fetch_assoc()['department_id'];
        header("Location: admin_dashboard.php");
        exit();
    }

    echo "Invalid credentials!";
}

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
        $_SESSION['email_address'] = $admin['email_address']; // Keeping this for reference

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


if(isset($_POST["super_admin"])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

    $sql="SELECT * FROM super_admins WHERE username='$username' and password='$password'";
    $result=$conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row= $result->fetch_assoc();
        $_SESSION['username']=$row['username'];
        header("location: superadmin_dashboard.php");
        exit();
    }
    else{
        echo "Incorret Username or Password";
    }
}
?>