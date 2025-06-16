<?php
$student_id = $_GET['student_id'] ?? 'N/A';
$status = $_GET['status'] ?? 'new';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Student ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin-top: 80px;
        }
        .card {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            display: inline-block;
        }
        .card h2 {
            color: #007BFF;
            margin-bottom: 10px;
        }
        .student-id {
            font-size: 2em;
            color: green;
            margin: 20px 0;
        }
        .buttons {
            margin-top: 30px;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 1rem;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn {
            background-color: #6c757d;
            color: white;
        }
        .print-btn {
            background-color: #28a745;
            color: white;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .card, .card * {
                visibility: visible;
            }
            .buttons {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <h2>
        <?php echo ($status === 'exists') ? 'You Are Already Registered!' : 'Registration Successful!'; ?>
    </h2>
    <p>Your unique <strong>Student ID</strong> is:</p>
    <div class="student-id"><?php echo htmlspecialchars($student_id); ?></div>

    <div class="buttons">
        <button class="back-btn" onclick="window.location.href='exam.html'">Back to Exam</button>
    </div>
</div>

</body>
</html>