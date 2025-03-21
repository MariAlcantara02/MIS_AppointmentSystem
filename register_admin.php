<?php 

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if super admin
    $super_admin = $conn->query("SELECT * FROM super_admins WHERE username='$username' AND password='$password'");
    if ($super_admin->num_rows > 0) {
        $_SESSION['role'] = 'super_admin';
        header("Location: super_admin_dashboard.php");
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

if(isset($_POST["admin"])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

    $sql="SELECT * FROM admins WHERE username='$username' and password='$password'";
    $result=$conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row= $result->fetch_assoc();
        $_SESSION['username']=$row['username'];
        header("location: admin_dashboard.php");
        exit();
    }
    else{
        echo "Incorret Username or Password";
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
