<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo "<br>Invalid Database or path";
    exit();
}

$paper_setter_id = $_POST['paper_setter_id'];
$paper_setter_name = $_POST['paper_setter_name'];

// Check for duplicate
$check_sql = "SELECT * FROM papersetter WHERE paper_setter_id = '$paper_setter_id'";
$result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($result) > 0) {
    header("Location: paperSetterForm.php?error=duplicate");
    exit();
} else {
    $sql = "INSERT INTO papersetter VALUES('$paper_setter_id', '$paper_setter_name')";
    if (mysqli_query($conn, $sql)) {
        header("Location: paperSetterForm.php?success=1");
        exit();
    } else {
        header("Location: paperSetterForm.php?error=insertfail");
        exit();
    }
}

mysqli_close($conn);
?>