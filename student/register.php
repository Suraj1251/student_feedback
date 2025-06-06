<?php
include "../includes/db.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    // $photo = $_FILES['photo']['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // $target = "../uploads/" . basename($photo);
    // move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $check = $conn->query("SELECT * FROM students WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$password')";
        // $sql = "INSERT INTO students (name, email, photo, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            $msg = "Registration successful! <a href='login.php'>Login now</a>";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="form-container">
    <h2>Student Registration</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <!-- <input type="file" name="photo" required> -->
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
