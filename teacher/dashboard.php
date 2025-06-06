<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$teacher_subject = $_SESSION['teacher_subject'];

$students = $conn->query("SELECT id, name FROM students");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $total = $_POST['total_classes'];
    $attended = $_POST['attended_classes'];

    $conn->query("INSERT INTO teacher_feedback (teacher_id, student_id, subject, rating, feedback)
                  VALUES ('$teacher_id', '$student_id', '$teacher_subject', '$rating', '$feedback')");

        $conn->query("INSERT INTO attendance (teacher_id, student_id, subject, total_classes, attended_classes)
                  VALUES ('$teacher_id', '$student_id', '$teacher_subject', '$total', '$attended')");
}

$feedbacks = $conn->query("
    SELECT s.name AS student_name, tf.rating, tf.feedback
    FROM teacher_feedback tf
    JOIN students s ON tf.student_id = s.id
    WHERE tf.teacher_id = '$teacher_id'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>


<nav>
    <ul>
        <li><a>Welcome, <?= $teacher_name ?> (<?= $teacher_subject ?>)</a></li>
        
        <li>
            <!-- <div style="text-align: right; margin-bottom: 10px;"> -->
 <a href="logout.php" >Logout</a>
<!-- </div> -->
        </li>
        <!-- <li> <a href="logout.php">Logout</a></li> -->
    </ul>
</nav>
<!-- <div style="text-align: right; margin-bottom: 10px;">
 <a href="logout.php" style="text-decoration: none; background-color: #004e92; color: white; padding: 10px 20px; border-radius: 5px text-align: right;">Logout</a>
</div> -->
 <div class="dashboard">
    <!-- <h2>Welcome, <?= $teacher_name ?> (<?= $teacher_subject ?>)</h2> -->

    <div class="card">
        <h3>Give Feedback</h3>
        <form method="post">
            <label>Select Student:</label>
            <select name="student_id" required>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select><br><br>
              <input type="number" name="total_classes" placeholder="Total Classes" min="1" required><br>
        <input type="number" name="attended_classes" placeholder="Classes Attended" min="0" required><br>
            <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5" required><br>
            <textarea name="feedback" placeholder="Enter feedback" required></textarea><br>
             <button type="submit">Submit Feedback</button>
            
            
 

        </form>
    </div>

    <div class="card">
        <h3>Your Feedbacks</h3>
        <?php while ($row = $feedbacks->fetch_assoc()): ?>
            <p><strong><?= $row['student_name'] ?>:</strong> <?= $row['feedback'] ?> (Rating: <?= $row['rating'] ?>/5)</p>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
