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
            
        $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
        
        if(mysqli_query($conn, $sql)){
            echo"Done";
        }
        mysqli_close($conn);
    ?>