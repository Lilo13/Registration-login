<?php

session_start();

$conn = mysqli_connect(hostname: "localhost", username: "root", password: "", database: "fast_travel");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    if (isset($_SESSION["tours"])) {
        $tour_array_id = array_column($_SESSION["tours"], "tours_id");
        if (!in_array($_GET["tour_id"], $tour_array_id)) {
            $count = count($_SESSION["tours"]);
            $tour_array = array(
                "tour_id" => $_GET["tour_id"],
                "tour_capacity" => $row["tour_capacity"],
                "location" => $_POST["location"],
                "price" => $_POST["price"],
                "pple_number" => $_POST["pple_number"],
            );

            $_SESSION["tours"][$count] = $tour_array;
            echo '<script> window.location="shopping.php"</script>';
        } else {
            echo '<script> alert("Product is already added to cart.")</script>';
            echo '<script> window.location="shopping.php"</script>';
        }
    } else {
        $tour_array = array(
            "tour_id" => $_GET["tour_id"],
            "tour_capacity" => $row["tour_capacity"],
            "location" => $_POST["location"],
            "price" => $_POST["price"],
            "pple_number" => $_POST["pple_number"],
        );
    
        $_SESSION["tours"][0] = $tour_array;
    }    
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["tours"] as $keys => $value){
            if ($value["tour_id"] == $_GET["tour_id"]) {
                unset($_SESSION["tours"][$keys]);
                echo '<script> alert("Product has been removed!.")</script>';
                echo '<script> window.location="shopping.php"</script>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shopping cart</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <style>
            table, th, tr{
                text-align: center;
                color:black;
                background-color: #efefef;
                padding: 2%;
            }
            h2{
                text-align: center;
                color: black;
                background-color: #efefef;
                padding: 2%;
            }
            table th{
                background-color: #efefef;
            }
        </style>
    </head>
    <body>

        <header style="background-color:darksalmon">
        <a href="Welcome.php"><img src="clogo.png" width="30px" height="30px"></a>
        
        <nav class="navbar">
            <a href="Welcome.php" style="color:black"> Welcome </a>
            <a href="about.php" style="color:black"> About us </a>
            <a href="shopping.php" style="color:black"> Book a tour </a>
            <a href="Profile.php" style="color:black"> Profile change </a>
        </nav>
        </header>

        <div class="image">
        <center><img src="logo.png"></center>
        </div>

        <div class="container-lg" style="width: 65%;">
            <h2>Shopping Cart</h2>

            <?php
            $query = "SELECT * FROM tours ORDER BY tour_id ASC";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    
                    <div class="col-md-3">
                        <form method="post" action="shopping.php">
                            <div class="loaction">
                                <img src="<?php echo $row['tour_pic'] ?>" class="img-responsive">
                                <h5 class="text-info"><?php echo $row['location']; ?></h5>
                                <h5 class="text-danger"><?php echo $row['price']; ?></h5>
                                <input type="text" name="pple_number" class="form-control" value="1">
                                <input type="hidden" name="location" value="<?php echo $row['location']; ?>">
                                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                                <input type="hidden" name="tour_id" value="<?php echo $row['tour_id']; ?>">
                                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
                            </div>
                        </form>
                    </div>
            <?php
        }
    }
?>

<div style="clear: both"></div>
<h3 class="title2"> Tour Details </h3>
<div class="table-responsive">
    <table class="table table-bordered">
    <tr>
        <th width="30%">Location</th>
        <th width="20%">Guests</th>
        <th width="15%">Price</th>
        <th width="20%">Total price</th>
        <th width="15%">Remove items</th>
    </tr>

    <?php
        if (!empty($_SESSION["tours"])) {
            $total = 0;
            foreach ($_SESSION["tours"] as $key => $value){
                ?>
            <tr>
                <td><?php echo isset($value["location"]) ? $value["location"]: ''; ?></td>
                <td><?php echo $value["pple_number"]; ?></td>
                <td><?php echo $value["price"]; ?></td>
                <td><?php echo $value["pple_number"]; ?></td>
                <td><a href="shopping.php?action=delete&tour_id=<?php echo $value["tour_id"]; ?>"><span class="text-danger"> Remove Item </span></a></td>
            </tr>

            <?php
            $total = $total + ($value["pple_number"] * $value["price"]);
            }
            ?>

            <tr>
                <td colspan="3" align="right"> Total </td>
                <th align="right"> $ <?php echo number_format($total, 2); ?> </th>
                <td></td>
            </tr>
            
            <?php
            }

        ?>
        </table>
        <br>
        <form method="post" action="checkout.php">
            <div class="form-btn">
                <input type="submit" class="btn btn-block btn-outline-primary" value="Checkout" name="checkout">
            </div>
</form>
</div>
        </div>
    </body>
</html>