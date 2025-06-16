<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_POST['student_id'] ?? '';

if ($student_id) {
    // Fetch student info
    $student_sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($student_sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $student_result = $stmt->get_result();

    if ($student_result->num_rows > 0) {
        $student = $student_result->fetch_assoc();

        // Fetch exam records
        $exam_sql = "SELECT subject, total_marks, score, exam_date FROM exams WHERE student_id = ?";
        $stmt = $conn->prepare($exam_sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $exam_result = $stmt->get_result();

        $totalScore = 0;
        $totalMarks = 0;
        $subjects = [];

        while ($row = $exam_result->fetch_assoc()) {
            $subjects[] = $row;
            $totalScore += $row['score'];
            $totalMarks += $row['total_marks'];
        }

        $overallPercent = $totalMarks > 0 ? round(($totalScore / $totalMarks) * 100) : 0;
    } else {
        echo "<h3>Student not found.</h3>";
        exit;
    }
} else {
    echo "<h3>No Student ID Provided.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Results</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .report-container {
            background: #fff;
            padding: 30px;
            max-width: 700px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        h2 {
            color: #222;
        }
        h3 {
            color: #666;
        }
        .percent-score {
            font-size: 30px;
            color: green;
            text-align: center;
            margin-bottom: 25px;
        }
        .section {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .section strong {
            display: inline-block;
            width: 130px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .analysis-box {
            background: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            font-size: 15px;
            color: #444;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            margin-top: 1.5rem;
            font-weight: 500;
            transition: var(--transition);
    }

    .back-link:hover {
            color: var(--accent-color);
    }
    </style>
</head>
<body>
<div class="report-container">
    <h2>Test Results</h2>
    <h3>Your performance summary</h3>
    <div class="percent-score"><?php echo $overallPercent; ?>%</div>

    <div class="section">
        <strong>Student Name:</strong> <?php echo $student['student_name']; ?><br>
        <strong>Student ID:</strong> <?php echo $student['student_id']; ?><br>
        <strong>Class:</strong> <?php echo $student['class']; ?><br>
        <strong>Email:</strong> <?php echo $student['email']; ?>
    </div>

    <div class="section">
        <h4>Exam Details</h4>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Exam Date</th>
                    <th>Total Marks</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $sub): ?>
                    <tr>
                        <td><?php echo ucfirst($sub['subject']); ?></td>
                        <td><?php echo $sub['exam_date']; ?></td>
                        <td><?php echo $sub['total_marks']; ?></td>
                        <td><?php echo $sub['score']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h4>Subject-wise Performance</h4>
        <ul>
            <?php foreach ($subjects as $sub): 
                $percent = round(($sub['score'] / $sub['total_marks']) * 100);
            ?>
                <li><?php echo ucfirst($sub['subject']) . ": " . $percent . "%"; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="analysis-box">
        <strong>Performance Analysis:</strong><br>
        <?php
            $lowest = null;
            foreach ($subjects as $sub) {
                $p = round(($sub['score'] / $sub['total_marks']) * 100);
                if ($lowest === null || $p < $lowest['percent']) {
                    $lowest = ['subject' => $sub['subject'], 'percent' => $p];
                }
            }
            echo "You performed well overall. ";
            if ($lowest) {
                echo "Consider improving in <strong>" . ucfirst($lowest['subject']) . "</strong> where you scored " . $lowest['percent'] . "%.";
            }
        ?>
    </div>
    
     <a href="result.html" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to result Page
        </a>
</div>
</body>
</html>
