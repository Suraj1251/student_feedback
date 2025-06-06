<?php
session_start();
include "../includes/db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM parents WHERE email='$email'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['parent_id'] = $row['id'];
            $_SESSION['parent_name'] = $row['name'];
            $_SESSION['student_id'] = $row['student_id'];
            header("Location: dashboard.php");
        } else {
            $msg = "Wrong password!";
        }
    } else {
        $msg = "Parent not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="form-container">
    <h2>Parent Login</h2>
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
