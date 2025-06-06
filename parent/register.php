<?php
include "../includes/db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT * FROM parents WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO parents (name, email, student_id, password) VALUES ('$name', '$email', '$student_id', '$password')";
        if ($conn->query($sql)) {
            $msg = "Registration successful! <a href='login.php'>Login</a>";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="form-container">
    <h2>Parent Registration</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="number" name="student_id" placeholder="Enter your Child's Student ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
