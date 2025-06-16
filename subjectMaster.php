<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("<br>Invalid Database or path");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectName = $_POST['subjectName'];

    $sql = "INSERT INTO SubjectMaster (subject_name) VALUES ('$subjectName')";

    if (mysqli_query($conn, $sql)) {
        header("Location: subjectMasterForm.php?success=1");
        exit();
    } else {
        if (mysqli_errno($conn) == 1062) {
            // Duplicate entry
            header("Location: subjectMasterForm.php?error=duplicate");
        } else {
            // Other error
            header("Location: subjectMasterForm.php?error=1");
        }
        exit();
    }
}

mysqli_close($conn);
?>