<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "config.php";

    $fullName = $_POST["fullname"];
    $userName = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    if (empty($fullName) || empty($userName) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match.";
    }

    $sql = "SELECT * FROM personal_info WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO personal_info (fullname, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if (mysqli_stmt_bind_param($stmt, "ssss", $fullName, $userName, $email, $password)) {
            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                $errors[] = "Something went wrong.";
            }
        } else {
            $errors[] = "Error preparing the statement.";
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .wrapper{
      width: 500px;
      padding: 20px;
    }
    .wrapper h2 {text-align: center}
    .wrapper form .form-group span {color: red;}
</style>
<body>
    <div class="container">
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        
        ?>
        <section class="container wrapper">
        <form action="Registration.php" method="post">
            <h1 class="display-4 pt-3">Registration Form</h1>
            <p>To create an account, please fill in your information.</p>

            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" class="form-control" name="fullname" id="fullname" value="<?= isset($fullName) ? $fullName : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" value="<?= isset($userName) ? $userName : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= isset($email) ? $email : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Enter Address:</label>
                <input type="text" class="form-control" name="address" id="address" value="<?= isset($address) ? $address : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="repeat_password">Repeat Password:</label>
                <input type="password" class="form-control" name="repeat_password" id="repeat_password" required>
            </div>

            <div class="form-btn">
            <input type="submit" class="btn btn-block btn-outline-primary" value="Register">
            </div>
        </form>
        </section>

        <div>
            <br>
            <div><p>Already Registered? <a href="Login.php">Login Here</a></p></div>
        </div>
    </div>
</body>
</html>
