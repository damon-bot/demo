<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the student ID from the form
$student_id = $_GET['student_id'] ?? '';

// Check if student ID is not empty
if (!empty($student_id)) {
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<p style='color: red;'>Invalid Student ID. Please register first.</p>";
        mysqli_close($conn);
        exit(); // Stop the rest of the code
    }

}


// Check if this is an AJAX request for a question (should be the very first check)
if (isset($_GET['subject']) && isset($_GET['q'])) {
    // Database connection (only needed for this AJAX request)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CBT"; // Using the database name CBT

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        // Return a JSON error response instead of dying with HTML
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database Connection failed: ' . $conn->connect_error]);
        exit;
    }

    $subjects = [
        'aptitude' => 'Aptitude',
        'reasoning' => 'Reasoning',
        'verbal_ability' => 'Verbal Ability'
    ];

    $subject = $_GET['subject'];
    $q = intval($_GET['q']);
    
    // Basic validation for subject
    if (!array_key_exists($subject, $subjects)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid subject selected.']);
        $conn->close();
        exit;
    }

    $table = $conn->real_escape_string($subject . '_questions');
    // Select the columns needed for the question and options
    // The column name for the question text in the database is 'question'
    $sql = "SELECT question_number, question, option_a, option_b, option_c, option_d FROM $table ORDER BY question_number ASC";
    
    $result = $conn->query($sql);
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        // Manually map the fetched data to the expected JSON structure for the JavaScript
        $questions[] = [
            'question_number' => $row['question_number'],
            'question_text' => $row['question'], // Map the 'question' column to 'question_text'
            'option_a' => $row['option_a'],
            'option_b' => $row['option_b'],
            'option_c' => $row['option_c'],
            'option_d' => $row['option_d'],
            // Include correct_option if needed on the client side, otherwise fetch separately for scoring
            // 'correct_option' => $row['correct_option'],
        ];
    }
    
    header('Content-Type: application/json'); // Ensure JSON header is set
    if (isset($questions[$q])) {
        echo json_encode([
            'question' => $questions[$q],
            'total' => count($questions),
            'current' => $q + 1
        ]);
    } else {
        echo json_encode(['error' => 'No question found for index: ' . $q]);
    }
    
    $conn->close();
    exit; // Exit after sending JSON response for question request
}

// Handle AJAX request for scoring
if (isset($_POST['exam_id']) && isset($_POST['answers']) && isset($_POST['subject'])) {
    // Database connection (only needed for this AJAX request)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CBT"; // Using the database name CBT

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
         header('Content-Type: application/json');
        echo json_encode(['error' => 'Database Connection failed: ' . $conn->connect_error]);
        exit;
    }

    $subjects = [
        'aptitude' => 'Aptitude',
        'reasoning' => 'Reasoning',
        'verbal_ability' => 'Verbal Ability'
    ];

    $exam_id = intval($_POST['exam_id']);
    $answers = json_decode($_POST['answers'], true);
    $subject = $_POST['subject'];

     // Basic validation for subject
    if (!array_key_exists($subject, $subjects)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid subject selected for scoring.']);
        $conn->close();
        exit;
    }

    // Fetch correct answers for the subject - explicitly select columns
    $table = $conn->real_escape_string($subject . '_questions');
    $sql = "SELECT question_number, correct_option FROM $table ORDER BY question_number ASC";
    $result = $conn->query($sql);
    $correct_answers = [];
    while ($row = $result->fetch_assoc()) {
        $correct_answers[$row['question_number']-1] = $row['correct_option']; // Store correct answers by 0-indexed question number
    }

    // Calculate score
    $score = 0;
    foreach ($answers as $q_index => $user_answer) {
        // Convert both correct and user answers to uppercase for case-insensitive comparison
        $correct_answer_upper = isset($correct_answers[$q_index]) ? strtoupper($correct_answers[$q_index]) : null;
        $user_answer_upper = strtoupper($user_answer);
        
        if ($correct_answer_upper !== null && $correct_answer_upper === $user_answer_upper) {
            $score++;
        }
    }

    // Update score in the exams table
    $update_sql = "UPDATE exams SET score = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $score, $exam_id);

    header('Content-Type: application/json');
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'score' => $score]);
    } else {
        echo json_encode(['error' => 'Error saving score: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
    exit; // Exit after sending JSON response for scoring request
}

// If not an AJAX request, proceed with standard HTML rendering for the main page

// Database connection (for initial page load and exam entry creation)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT"; // Using the database name CBT

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$subjects = [
    'aptitude' => 'Aptitude',
    'reasoning' => 'Reasoning',
    'verbal_ability' => 'Verbal Ability'
];

// Get subject, exam_id, total_marks, student_id, and exam_date from URL parameters
$selected_subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;
$total_marks = isset($_GET['total_marks']) ? intval($_GET['total_marks']) : 0;
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$exam_date = isset($_GET['exam_date']) ? $_GET['exam_date'] : '';

// Validate parameters for initial page load
if (!array_key_exists($selected_subject, $subjects)) {
    $error_message = "Invalid subject specified.";
}

if ($exam_id <= 0) {
     $error_message = "Invalid exam ID.";
}

if ($total_marks <= 0) {
    $error_message = "Invalid total marks specified.";
}

if (empty($student_id)) {
     $error_message = "Student ID is required.";
}

if (empty($exam_date)) {
    $error_message = "Exam date is required.";
}

// Store exam entry in database if all required parameters are present and valid
// This block only runs on the initial page load, not during AJAX calls for questions/scoring
if (!isset($error_message)) { // Only attempt to store if no validation errors on initial load
    // First check if an exam entry already exists for this student, subject, and date
    $check_sql = "SELECT id FROM exams WHERE student_id = ? AND subject = ? AND exam_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("sss", $student_id, $selected_subject, $exam_date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Insert new exam entry
        $insert_sql = "INSERT INTO exams (student_id, subject, exam_date, total_marks, score) VALUES (?, ?, ?, ?, 0)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssi", $student_id, $selected_subject, $exam_date, $total_marks);
        
        if ($insert_stmt->execute()) {
            $exam_id = $conn->insert_id; // Get the newly created exam ID for use in JavaScript
        } else {
            $error_message = "Error creating exam entry: " . $conn->error;
        }
        $insert_stmt->close();
    } else {
        // Get existing exam ID for use in JavaScript
        $row = $result->fetch_assoc();
        $exam_id = $row['id'];
    }
    $check_stmt->close();
}

// Close the database connection if it was opened for the initial page load and not closed by an AJAX exit
if ($conn && $conn->ping()) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f7f7f7; /* Light grey background */
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .test-container {
            max-width: 700px; /* Adjust max-width as needed */
            margin: 3rem auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .test-heading {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 2rem;
            letter-spacing: 2px;
        }
         .subject-display {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 2rem;
        }
        .question-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(74, 144, 226, 0.08);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        .question-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: #222b45;
        }
        .options-list {
            list-style: none;
            padding: 0;
            margin-bottom: 2rem;
        }
        .options-list li {
            margin-bottom: 0.8rem;
        }
        .options-list label {
            font-size: 1.08rem;
            cursor: pointer;
            padding-left: 0.5rem;
        }
        .nav-btns {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
        }
        .nav-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            border: 1px solid #dbeafe;
            background: #f1f5fb;
            color: #222b45;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .nav-btn:disabled {
            background: #e5e7eb;
            color: #b0b0b0;
            cursor: not-allowed;
        }
        .nav-btn.next, .nav-btn.finish {
            background: #2563eb;
            color: #fff;
            border: none;
        }
        .score-display {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #4CAF50;
            margin-top: 3rem;
        }
        @media (max-width: 600px) {
            .test-container {
                padding: 1rem 0.3rem;
            }
            .question-card {
                padding: 1rem 0.5rem;
            }
            .test-heading {
                font-size: 1.3rem;
            }
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
    <div class="test-container">
        <div class="test-heading">TEST</div>
        <?php if (isset($error_message)): ?>
            <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
        <?php else: ?>
            <div class="subject-display">Subject: <?php echo $subjects[$selected_subject]; ?></div>
            <div id="question-area">
                <div style="text-align:center;color:#aaa;">Loading questions...</div>
            </div>
        <?php endif; ?>

            <a href="test.html" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Test Page
            </a>

    </div>
    <script>
        const questionArea = document.getElementById('question-area');
        let currentSubject = "<?php echo $selected_subject; ?>";
        let examId = <?php echo $exam_id; ?>;
        let currentQ = 0;
        let totalQ = <?php echo $total_marks; ?>;
        let answers = {};

        // Load initial question on page load
        if (currentSubject && examId > 0 && totalQ > 0) {
            loadQuestion();
        }

        function loadQuestion() {
            if (!currentSubject) return;
            fetch(`test.php?subject=${currentSubject}&q=${currentQ}`)
                .then(res => {
                     // Check if response is not ok (e.g., 404, 500)
                    if (!res.ok) {
                        // Attempt to read the response as text to include in the error
                        return res.text().then(text => { throw new Error(`HTTP error! status: ${res.status}, body: ${text}`) });
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Data received for question:', data);
                    if (data.error) {
                        questionArea.innerHTML = `<div style='color:#f44336;text-align:center;'>${data.error}</div>`;
                        return;
                    }
                    
                    // Only show questions up to total marks
                    if (currentQ >= totalQ) {
                        submitAnswers();
                        return;
                    }

                    const question = data.question;
                    // totalQ is already set from the PHP variable on page load
                    // No need to update it here based on data.total
                    
                    let optionsHtml = '';
                    ['a', 'b', 'c', 'd'].forEach(option => {
                        optionsHtml += `
                            <li>
                                <input type="radio" name="answer" id="option${option}" value="${option}" 
                                    ${answers[currentQ] === option ? 'checked' : ''}>
                                <label for="option${option}">${question[`option_${option}`]}</label>
                            </li>`;
                    });

                    questionArea.innerHTML = `
                        <div class="question-card">
                            <div class="question-title">Question ${currentQ + 1}: ${question.question_text}</div>
                            <ul class="options-list">
                                ${optionsHtml}
                            </ul>
                            <div class="nav-btns">
                                <button class="nav-btn prev" onclick="prevQuestion()" ${currentQ === 0 ? 'disabled' : ''}>
                                    Previous
                                </button>
                                <button class="nav-btn next" onclick="nextQuestion()">
                                    ${currentQ === totalQ - 1 ? 'Finish' : 'Next'}
                                </button>
                            </div>
                        </div>`;

                    // Add event listeners for radio buttons after they are added to the DOM
                    document.querySelectorAll('input[name="answer"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            answers[currentQ] = this.value;
                        });
                    });
                })
                .catch(error => {
                    console.error('Error loading question:', error);
                    questionArea.innerHTML = `<div style='color:#f44336;text-align:center;'>Error loading question: ${error.message}</div>`;
                });
        }

        function prevQuestion() {
            if (currentQ > 0) {
                const selectedOption = document.querySelector('input[name="answer"]:checked');
                 if (selectedOption) {
                    answers[currentQ] = selectedOption.value;
                }
                currentQ--;
                loadQuestion();
            }
        }

        function nextQuestion() {
            const selectedOption = document.querySelector('input[name="answer"]:checked');
            if (selectedOption) {
                answers[currentQ] = selectedOption.value;
            } else {
                 // Optionally, alert the user to select an answer
                 // alert('Please select an answer before proceeding.');
                 // return;
            } // Allow proceeding without selecting for now

            if (currentQ < totalQ - 1) {
                currentQ++;
                loadQuestion();
            } else {
                submitAnswers();
            }
        }

        function submitAnswers() {
             fetch('test.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `exam_id=${examId}&subject=${currentSubject}&answers=${JSON.stringify(answers)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    questionArea.innerHTML = `<div class='score-display'>Your Score: ${data.score} / ${totalQ}</div>`;
                } else {
                    questionArea.innerHTML = `<div style='color:#f44336;text-align:center;'>Error submitting answers: ${data.error}</div>`;
                }
            })
            .catch(error => {
                console.error('Error submitting answers:', error);
                questionArea.innerHTML = '<div style="color:#f44336;text-align:center;">An error occurred during submission.</div>';
            });
        }


    </script>
</body>
</html>