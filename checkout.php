<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "fast_travel");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {

    // Create a unique order ID (you can customize this based on your needs)
    $order_id = uniqid();

    if (isset($_SESSION["tours"]) && is_array($_SESSION["tours"])) {
        // Your existing code for updating tour capacities
        foreach ($_SESSION["tours"] as $key => $value) {
            $tour_id = $value["tour_id"];
            $pple_number = $value["pple_number"];
            
            // Update the tour capacity in the database
            $update_query = "UPDATE tours SET tour_capacity = tour_capacity - ? WHERE tour_id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "ii", $pple_number, $tour_id);
            $result = mysqli_stmt_execute($stmt);
            
            if (!$result) {
                error_log("Error updating record: " . mysqli_error($conn) . ". Query: " . $update_query);
            }
        }
        
        // Clear the shopping cart after checkout
        unset($_SESSION["tours"]);

        header("Location: confirmation.php?order_id=" . $order_id);
        exit();
    } else {
        // Handle the case when there are no tours in the session
        echo "No tours in the shopping cart.";
    }
} else {
    header("Location: shopping.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Add your CSS styles if needed -->
</head>
<body>
    <div>
        <h1>Invoice</h1>
        <p>Order ID: <?php echo $order_id; ?></p>

        <table border="1">
            <tr>
                <th>Location</th>
                <th>Guests</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION["tours"] as $key => $value) {
                ?>
                <tr>
                    <td><?php echo $value["location"]; ?></td>
                    <td><?php echo $value["pple_number"]; ?></td>
                    <td><?php echo $value["price"]; ?></td>
                    <td><?php echo $value["pple_number"] * $value["price"]; ?></td>
                </tr>
                <?php
                $total += $value["pple_number"] * $value["price"];
            }
            ?>
            <tr>
                <td colspan="3" align="right">Total</td>
                <td><?php echo $total; ?></td>
            </tr>
        </table>

        <p>Thank you for booking with us!</p>
    </div>
</body>
</html>
