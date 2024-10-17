<?php
session_start();
define('BASE_URL', '/mypham/');
include '../includes/db.php'; // Includes the database connection using PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check if email or phone already exists
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email OR phone = :phone");
    $stmt->execute(['email' => $email, 'phone' => $phone]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Email or phone number already registered
        echo "<script>
        alert('Email, username, or phone number already registered.');
        window.location.href = '" . BASE_URL . "pages/auth.php';
        </script>";
        exit();
    } else {
        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO user (username, fullname, email, phone, password) VALUES (:username, :fullname, :email, :phone, :password)");
        $stmt->execute([
            'username' => $username,
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password
        ]);

        // Get the last inserted ID
        $user_id = $pdo->lastInsertId();

        // Set session variables for logged-in user
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        // Redirect to the auth page or the next step
        header('Location: ' . BASE_URL . 'pages/auth.php');
        exit();
    }
}
?>
