<?php
include "../includes/db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT * FROM teachers WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "Email already registered.";
    } else {
        $sql = "INSERT INTO teachers (name, email, subject, password)
                VALUES ('$name', '$email', '$subject', '$password')";
        if ($conn->query($sql)) {
            $msg = "Registered successfully! <a href='login.php'>Login now</a>";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="form-container">
    <h2>Teacher Registration</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
