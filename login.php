<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "cbt");

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['email'] = $email;

    // If user_id is not yet assigned, assign a new one
    if (empty($user['user_id'])) {
        $countRes = mysqli_query($conn, "SELECT COUNT(user_id) as total FROM users WHERE user_id IS NOT NULL");
        $countRow = mysqli_fetch_assoc($countRes);
        $nextId = 1001 + $countRow['total'];
        $newId = "CBT" . $nextId;

        mysqli_query($conn, "UPDATE users SET user_id='$newId' WHERE id=" . $user['id']);
        $user['user_id'] = $newId;
    }

    $_SESSION['user_id'] = $user['user_id'];

    header("Location: user_id.php");
    exit();
} else {
    echo "Unauthorized user or incorrect password.";
}
?>