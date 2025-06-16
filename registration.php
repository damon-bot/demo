<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$user_id = $_POST['user_id'];
$student_name = $_POST['student_name'];
$class = $_POST['class'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];

// Step 1: Check that email from users table matches entered email
$user_check_sql = "SELECT email FROM users WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_check_sql);

if (mysqli_num_rows($user_result) === 0) {
    echo "<script>
        alert('User ID not found in login records.');
        window.location.href = 'registration_form.html'; // Update with your actual form
    </script>";
    exit();
}

$user_data = mysqli_fetch_assoc($user_result);
$registered_email = strtolower(trim($user_data['email']));
$entered_email = strtolower(trim($email));

if ($registered_email !== $entered_email) {
    echo "<script>
        alert('Error: You must use the same email used during login.');
        window.location.href = 'registration.html'; // Update with your actual form
    </script>";
    exit();
}

// Step 2: Check if already registered
$check_sql = "SELECT student_id FROM students WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $existing_student_id = $row['student_id'];
    header("Location: show_student_id.php?student_id=$existing_student_id&status=exists");
    exit();
}

// Step 3: Generate student ID
$uid_num = preg_replace('/\D/', '', $user_id);
$last_three_digits = substr($uid_num, -3);
$student_id = "TEST001" . str_pad($last_three_digits, 3, "0", STR_PAD_LEFT);

// Step 4: Insert new registration
$sql = "INSERT INTO students (student_id, user_id, student_name, class, mobile, email)
        VALUES ('$student_id', '$user_id', '$student_name', '$class', '$mobile', '$email')";

if (mysqli_query($conn, $sql)) {
    header("Location: show_student_id.php?student_id=$student_id&status=new");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>