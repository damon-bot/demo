<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CBT";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        echo "<BR>Invalid Database or path";
    }
        
    $sql = "CREATE TABLE IF NOT EXISTS exams (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL ,
        subject VARCHAR(50) NOT NULL,
        exam_date DATE NOT NULL,
        total_marks INT NOT NULL,
        score INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
        
    if(mysqli_query($conn, $sql)){
        echo"Done";
    }
    mysqli_close($conn);
?>