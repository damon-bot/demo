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
            
        $sql = "CREATE TABLE  IF NOT EXISTS SubjectMaster (
            subject_code INT AUTO_INCREMENT PRIMARY KEY,
            subject_name VARCHAR(255) NOT NULL UNIQUE
          )";
        
        if(mysqli_query($conn, $sql)){
            echo"Done";
        }
        mysqli_close($conn);
    ?>