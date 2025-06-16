<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .result-heading {
        text-align: center;
        font-size: 50px;
        font-weight: bold;
        color: #28a745;
        margin-top: 40px;
        margin-bottom: 10px;
    }    
    .result-box {
        border: 2px solid #28a745;
        padding: 20px;
        width: fit-content;
        margin: 40px auto;
        border-radius: 10px;
        background-color: #f9fff9;
        box-shadow: 0 0 10px rgba(0, 128, 0, 0.1);
    }

    .result-box h3 {
        color: green;
        margin-bottom: 15px;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        text-align: center;
    }

    th, td {
        padding: 10px 15px;
        border: 1px solid #ccc;
    }
     .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 30%;
            background: #6c757d;
            color: #fff;
            padding: 0.9rem 0;
            border: none;
            border-radius: 7px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            margin-top: 1rem;
            transition: background 0.2s;
            cursor: pointer;
        }
        .back-button:hover {
            background: #5a6268;
        }
        .back-button i {
            font-size: 1.1rem;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem; 
        }

      
</style>
</head>
<body>
    
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $subject = $_POST['subject'];
    $entered_total_marks = (int)$_POST['total_marks'];

    // Step 1: Check student ID and name
    $student_sql = "SELECT * FROM students WHERE student_id = '$student_id' AND student_name = '$student_name'";
    $student_result = $conn->query($student_sql);

    if ($student_result->num_rows > 0) {
        // Step 2: Get ALL matching records from exams table
        $exam_sql = "SELECT subject, total_marks, score, exam_date FROM exams 
                     WHERE student_id = '$student_id' 
                       AND subject = '$subject' 
                       AND total_marks = $entered_total_marks";
        $exam_result = $conn->query($exam_sql);

        if ($exam_result->num_rows > 0) {

            echo "<div class='result-heading'>Result</div>";
            echo "<div class='result-box'>";
            echo "<h3>✅ " . $exam_result->num_rows . " matching record(s) found</h3>";
            echo "<table>
                    <tr>
                        <th>Subject</th>
                        <th>Total Marks</th>
                        <th>Score</th>
                        <th>Exam Date</th>
                    </tr>";

            while ($row = $exam_result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['subject']}</td>
                        <td>{$row['total_marks']}</td>
                        <td>{$row['score']}</td>
                        <td>{$row['exam_date']}</td>
                    </tr>";
                }   
            echo "</table>";
            echo "</div>";
            echo "
                <div style='
                        display: flex;
                        justify-content: center;
                        margin-top: 2rem;
               '>
                    <a href='result.html' class='back-button'>Back to Result Page</a>
                </div>";

        } else {
            echo "<h3 style='color:red;'>❌ No exam records found for these exact details.</h3>";
        }
    } else {
        echo "<h3 style='color:red;'>❌ Student ID and Name do not match.</h3>";
    }
}

$conn->close();
?>
