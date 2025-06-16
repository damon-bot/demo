<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['student_id'])) {
    $student_id = $conn->real_escape_string($_GET['student_id']);

    $query = "SELECT student_name FROM students WHERE student_id = '$student_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo $result->fetch_assoc()['student_name'];
    } else {
        echo "";
    }
}

$conn->close();
?>
