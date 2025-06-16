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

        // Create the students table
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(50)  UNIQUE,
            student_name VARCHAR(100) NOT NULL,
            class VARCHAR(50) NOT NULL,
            mobile VARCHAR(20) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        
        if(mysqli_query($conn, $sql)){
            echo"Done";
        }
        mysqli_close($conn);
?>

