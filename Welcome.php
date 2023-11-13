<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
   header("Location: Login.php");
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
    <!--Welcome section -->
    <section class="Welcome">
        <div class="swiper home-slider">
            <div class="swipper-wrapper">
            <div class="content">
            <center><br><p>In need of a vacation!<br> Or maybe just want to get out of town!<br> Your definetly in the right place.<br></p>
            <span><b>EXPLORE, DISCOVER, TRAVEL</b></span><br>
            <P>Discover new places, make your tour worth while, get your friends and family in on it.</P></center>
            </div>
            </div>
        </div>

    
    </div>
    
</body>
</html>