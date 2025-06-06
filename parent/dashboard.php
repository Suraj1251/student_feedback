<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$parent_name = $_SESSION['parent_name'];

$student = $conn->query("SELECT * FROM students WHERE id='$student_id'")->fetch_assoc();
$feedbacks = $conn->query("
    SELECT t.name AS teacher_name, tf.subject, tf.rating, tf.feedback
    FROM teacher_feedback tf
    JOIN teachers t ON tf.teacher_id = t.id
    WHERE tf.student_id = '$student_id'
");

$attendance = $conn->query("SELECT subject, attended_classes, total_classes FROM attendance WHERE student_id = '$student_id'");

$parentFeedback = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['parent_feedback'])) {
    $feedback = $_POST['parent_feedback'];
    $conn->query("INSERT INTO parent_feedback (parent_id, student_id, feedback) VALUES ('{$_SESSION['parent_id']}', '$student_id', '$feedback')");
    $parentFeedback = "Feedback submitted.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body>



<nav>
    <ul>
        <li><a>Welcome, <?= $parent_name ?></a></li>
        
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
    <!-- <h2>Welcome, <?= $parent_name ?></h2> -->


    <div class="card">
        <h3>Child Information</h3>
        <p>Name: <?= $student['name'] ?></p>
        <!-- <img src="../uploads/<?= $student['photo'] ?>" width="100"> -->
    </div>
    

    <div class="card">
        <h3>Attendance Overview</h3>
        <?php if ($attendance->num_rows > 0): ?>
            <?php while ($row = $attendance->fetch_assoc()): ?>
                <p>
                    <strong><?= $row['subject'] ?></strong><br>
                    Attended: <?= $row['attended_classes'] ?> out of <?= $row['total_classes'] ?> classes
                </p>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No attendance data available yet.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Teacher Feedback</h3>
        <?php while ($row = $feedbacks->fetch_assoc()): ?>
            <p>
                <strong><?= $row['teacher_name'] ?> (<?= $row['subject'] ?>)</strong><br>
                Rating: <?= $row['rating'] ?>/5<br>
                Feedback: <?= $row['feedback'] ?>
            </p><hr>
        <?php endwhile; ?>
    </div>

    <div class="card">
        <h3>Give Your Feedback</h3>
        <form method="post">
            <textarea name="parent_feedback" rows="4" placeholder="Your feedback" required></textarea><br>
            <button type="submit">Submit Feedback</button>
        </form>
        <p><?= $parentFeedback ?></p>
    </div>
</div>
</body>
</html>
