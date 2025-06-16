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
            
        $sql = "CREATE TABLE QuestionBankMaster (
            question_bank_code VARCHAR(255) NOT NULL UNIQUE,
            subject VARCHAR(255) NOT NULL,
            paper_setter VARCHAR(255) NOT NULL
          )";
        
        if(mysqli_query($conn, $sql)){
            echo"Done";
        }
        mysqli_close($conn);
    ?>