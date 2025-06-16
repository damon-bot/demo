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

   
        $sql = "CREATE TABLE IF NOT EXISTS aptitude_questions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                question_number INT NOT NULL,
                question TEXT NOT NULL,
                option_a TEXT NOT NULL,
                option_b TEXT NOT NULL,
                option_c TEXT NOT NULL,
                option_d TEXT NOT NULL,
                correct_option CHAR(1) NOT NULL,
                explanation TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
          )";
            
       
        
        if(mysqli_query($conn, $sql)){
            echo"Done";
        }
        mysqli_close($conn);
?>