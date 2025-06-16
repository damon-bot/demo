<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBT";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $subject = $_POST['subject'];
    $question_number = $_POST['question_number'];
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $explanation = $_POST['explanation'];

    // Determine which table to use based on subject
    $table_name = $subject . "_questions";

    // Prepare SQL statement
    $sql = "INSERT INTO $table_name (question_number, question, option_a, option_b, option_c, option_d, correct_option, explanation) 
            VALUES (:question_number, :question, :option_a, :option_b, :option_c, :option_d, :correct_option, :explanation)";
    
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':question_number', $question_number);
    $stmt->bindParam(':question', $question);
    $stmt->bindParam(':option_a', $option_a);
    $stmt->bindParam(':option_b', $option_b);
    $stmt->bindParam(':option_c', $option_c);
    $stmt->bindParam(':option_d', $option_d);
    $stmt->bindParam(':correct_option', $correct_option);
    $stmt->bindParam(':explanation', $explanation);

    // Execute the statement
    $stmt->execute();

    // Redirect back with success message
    header("Location: questionBankForm.html?success=1");
    exit();

} catch(PDOException $e) {
    // Redirect back with error message
    header("Location:questionBankForm.html?error=1");
    exit();
}

$conn = null;
?> 