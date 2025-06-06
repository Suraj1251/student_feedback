<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
// $student_photo = $_SESSION['student_photo'];
$student_id = $_SESSION['student_id'];

$feedbacks = $conn->query("
    SELECT t.name AS teacher_name, tf.subject, tf.rating, tf.feedback
    FROM teacher_feedback tf
    JOIN teachers t ON tf.teacher_id = t.id
    WHERE tf.student_id = '$student_id'
");
$attendance = $conn->query("
    SELECT subject, total_classes, attended_classes 
    FROM attendance 
    WHERE student_id = '$student_id'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <ul>
        <li><a>Welcome, <?= $student_name ?></a></li>
        
        <li>
            <!-- <div style="text-align: right; margin-bottom: 10px;"> -->
 <a href="logout.php">Logout</a>
<!-- </div> -->
        </li>
        <!-- <li> <a href="logout.php">Logout</a></li> -->
    </ul>
</nav>


<div class="dashboard">
    
    <!-- <img src="../uploads/<?= $student_photo ?>" width="100" alt="Student Photo"> -->
     <h4>Student ID : <?=$student_id?></h2>

         <div class="card">
        <h3>Attendance Report</h3>
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
        <h3>Feedback from Teachers</h3>
        <?php if ($feedbacks->num_rows > 0): ?>
            <?php while ($row = $feedbacks->fetch_assoc()): ?>
                <p>
                    <strong><?= $row['teacher_name'] ?> (<?= $row['subject'] ?>)</strong><br>
                    Rating: <?= $row['rating'] ?>/5 <br>
                    Feedback: <?= $row['feedback'] ?>
                </p>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback available yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
