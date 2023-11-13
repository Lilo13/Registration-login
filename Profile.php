<?php
 
 session_start();
 require_once "config.php";

 if(isset($_POST['edit'])) {
    $id=$_SESSION['id'];
    $username=$_POST['username'];
    $fullname=$_POST['fullname'];
    $email=$_POST['email'];
    $address=$_POST['address'];
    $password=$_POST['password'];

    $select_query= "SELECT * from personal_info where ID=?";
    $stmt_select = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result_select = mysqli_stmt_get_result($stmt_select);

    if($row = mysqli_fetch_assoc($result_select)) {
        // Update the user profile
        $update_query = "UPDATE personal_info SET username=?, fullname=?, email=?, address=?, password=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, "sssssi", $username, $fullname, $email, $address, $password, $id);
        $result_update = mysqli_stmt_execute($stmt_update);

        if ($result_update) {
            // Successful update
            header('location: Profile.php?status=success');
            exit();
        } else {
            // Failed update
            header('location: Profile.php?status=error');
            exit();
        }
    } else {
        // User not found
        header('location: Profile.php?status=user_not_found');
        exit();
    }
    
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>

</head>
<body>
    <div class="container-lg" >

    <!--Header section -->
    <section class="header" style="background-color:darksalmon">
    <a href="Welcome.php"><img src="clogo.png" width="30px" height="30px"></a>
        
        <nav class="navbar">
                <a href="Welcome.php" style="color:black"> Welcome </a>
                <a href="about.php" style="color:black"> About us </a>
                <a href="shopping.php" style="color:black"> Book a tour </a>
                <a href="Profile.php" style="color:black"> Profile change </a>
        </nav>
        
    </section>

    <div class="image">
        <center><img src="logo.png"></center>
    </div>

    <h1><center> Welcome to Fast Travel </center></h1>
    <br>

    <div class="container-lg">
        <form action="Profile.php" method="post">
        <div class="flex">
            <div class="form-group">
                <label for="username">Username :</label>
                <input type="text" class="form-control" name="username" id="username">
            </div>

            <div class="form-group">
                <label for="fullname">Full Name :</label>
                <input type="text" class="form-control" name="fullname" id="fullname">
            </div>

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>

            <div class="form-group">
                <label for="address">Address :</label>
                <input type="text" class="form-control" name="address" id="address">
            </div>

            <div class="form-group">
                <label for="password">Password :</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            
            <input type="submit" name="edit">
        </form>
    </div>
</body>
</html>