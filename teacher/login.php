<?php
session_start();
include "../includes/db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = $conn->query("SELECT * FROM teachers WHERE email='$email'");
    if ($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['teacher_name'] = $row['name'];
            $_SESSION['teacher_subject'] = $row['subject'];
            header("Location: dashboard.php");
        } else {
            $msg = "Incorrect password.";
        }
    } else {
        $msg = "Teacher not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="form-container">
    <h2>Teacher Login</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
         <br><br>
         Don't have an account? <a href='register.php' style="text-decoration: none;">Register now</a>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
