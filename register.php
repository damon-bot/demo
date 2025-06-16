<?php
$host = "localhost";
$username = "root";
$password = ""; // your MySQL password
$dbname = "CBT";

// Connect to DB
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize input
$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$pass = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];

// Check if passwords match
if ($pass !== $confirm_pass) {
    die("Passwords do not match.");
}

// Hash password
$hashed_password = password_hash($pass, PASSWORD_BCRYPT);

// Insert into DB
$stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful. <a href='login.html'></a>";

     // Redirect back with success message
    header("Location: login.html?success=1");
    exit();


} else {
    echo "Error: " . $stmt->error;
      header("Location: register.html?error=1");
    exit();
}


$stmt->close();
$conn->close();
?>