<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student ID</title>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .main-box {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 400px;
        }
        .main-box h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .student-id {
            font-size: 36px;
            font-weight: bold;
            color: #2c7be5;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .home-box {
            margin-top: 30px;
            padding: 10px;
            background-color: #e7f1ff;
            border-radius: 10px;
        }
        .home-box a {
            text-decoration: none;
            color: #2c7be5;
            font-weight: bold;
        }
        .home-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="main-box">
        <h2>Welcome, <?php echo $_SESSION['email']; ?>!</h2>
        <p>Your Unique User ID is:</p>
        <div class="user-id"><?php echo $user_id; ?></div>
        
        <div class="home-box">
            <a href="home.html">⬅ Back to Home</a>
        </div>
    </div>
</body>
</html>