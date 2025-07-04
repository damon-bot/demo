<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Subject Master</title>
  <style>
    /* Base styling */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #e3f2fd, #ffffff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      background-color: #ffffff;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      animation: slideIn 0.6s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      color: #555;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      transition: border 0.3s, box-shadow 0.3s;
    }

    input[type="text"]:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }
           .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
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
            font-size: 1.1rem;}

    @media (max-width: 600px) {
      .form-container {
        padding: 20px;
      }

      input[type="text"], button {
        font-size: 14px;
        padding: 10px;
      }

      input, button { padding: 10px; margin: 5px 0; }
      table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; }
      .success { color: green; }
      .error { color: red; }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Question Bank Master</h2>
    <form action="questionBankMaster.php" method="POST">

      <label for="question_bank_code">Question Bank Code</label>
      <input type="text" id="question_bank_code" name="question_bank_code" placeholder="Enter Question Bank Code" required>

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" placeholder="Enter Subject Name" required>

      <label for="paper_setter">Paper Setter</label>
      <input type="text" id="paper_setter" name="paper_setter" placeholder="Enter Paper Setter Name" required>

      <button type="submit">Save</button><br>
      <a href="master.html" class="back-button">
            <i class="fas fa-arrow-left"></i>
              Back to Master
      </a>
    </form>


  <!-- Show messages -->
  <?php
    if (isset($_GET['success'])) echo "<p class='success'>Data saved!</p>";
    if (isset($_GET['error'])) {
      if ($_GET['error'] == "duplicate") echo "<p class='error'>Duplicate code. Try another.</p>";
      else echo "<p class='error'>Something went wrong.</p>";
    }
  ?>

<!-- Show Table -->
       <h3>All Subjects</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>Question Bank Code</th>
                <th>Subject</th>
                <th>paper Setter</th>
             </tr>
            <?php

            $conn = mysqli_connect("localhost", "root", "", "CBT");
            if ($conn) {
                $result = mysqli_query($conn, "SELECT * FROM QuestionBankMaster");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>{$row['question_bank_code']}</td><td>{$row['subject']}</td><td>{$row['paper_setter']}</td></tr>";
                }
                mysqli_close($conn);
            }
            else{
              echo "<p> No Subject added yet.</p>";
            }
            ?>
        </table>
    </div>


</body>
</html>

