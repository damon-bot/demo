<?php
$conn = mysqli_connect("localhost", "root", "", "CBT");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$code = $_POST['question_bank_code'];
$subject = $_POST['subject'];
$setter = $_POST['paper_setter'];

// Check duplicate
$check = mysqli_query($conn, "SELECT * FROM QuestionBankMaster WHERE question_bank_code = '$code'");
if (mysqli_num_rows($check) > 0) {
    header("Location: questionBankMasterForm.php?error=duplicate");
    exit;
}

// Insert
$sql = "INSERT INTO QuestionBankMaster (question_bank_code, subject, paper_setter)
        VALUES ('$code', '$subject', '$setter')";
if (mysqli_query($conn, $sql)) {
    header("Location: questionBankMasterForm.php?success=1");
} else {
    header("Location: questionBankMasterForm.php?error=1");
}
exit;
?>