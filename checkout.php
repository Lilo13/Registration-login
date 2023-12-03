<?php

session_start();

require_once "config.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {

    // Create a booking ID
    $booking_id = uniqid();

    if (isset($_SESSION["tours"]) && is_array($_SESSION["tours"])) {
         // Start a transaction to ensure data consistency
         mysqli_begin_transaction($conn);

         try {
            foreach ($_SESSION["tours"] as $key => $value) {
                $tour_id = $value["tour_id"];
                $pple_number = $value["pple_number"];
                
                // Update tour capacity in the database
                $update_query = "UPDATE tours SET tour_capacity = tour_capacity - ? WHERE tour_id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "ii", $pple_number, $tour_id);
                $result = mysqli_stmt_execute($stmt);
                
                if (!$result) {
                    throw new Exception("Error updating tour capacity. Please contact support.");
            }
        }
        
        // Insert booking data into the 'booking' table
        $insert_booking_query = "INSERT INTO booking (booking_id, location, pple_number, total_amount) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_booking_query);
        mysqli_stmt_bind_param($stmt, "ssdi", $booking_id, $value['location'], $pple_number, $total);
        $total = 0;
        foreach ($_SESSION["tours"] as $key => $value) {
            $total += $value['pple_number'] * $value["price"];
        }
        $result = mysqli_stmt_execute($stmt);
        
        if (!$result) {
            throw new Exception("Error inserting booking data. Please contact support.");
        }

        // Commit the transaction
        mysqli_commit($conn);
        
        // Display the invoice on the checkout page
        echo <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Invoice</title>
                </head>
                    
                <body>
                    <div>
                        <h1>Invoice</h1>
                        <p>Booking ID: $booking_id</p>
                        
                        <table border="1">
                        <tr>
                            <th>Location</th>
                            <th>Guests</th>
                            <th>Price</th>
                            <th>Total</th>
                            </tr>
                            
                HTML;

                        $total = 0;
                        foreach ($_SESSION["tours"] as $key => $value) {
                            echo "<tr>";
                            echo "<td>{$value['location']}</td>";
                            echo "<td>{$value['pple_number']}</td>";
                            echo "<td>{$value['price']}</td>";
                            echo "<td>" . ($value['pple_number'] * $value['price']) . "</td>";
                            echo "</tr>";
                            $total += $value['pple_number'] * $value["price"];
                        }
                        
                        echo <<<HTML
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td>$total</td>
                        </tr>
                    </table>
                    
                    <p>Thank you for booking with us!</p>
                </div>
            </body>
            </html>
                
HTML;        
        // Clear shopping cart after checkout
        unset($_SESSION["tours"]);

    } catch (Exception $e) {
        mysqli_rollback($conn);

        echo $e->getMessage();
        error_log("Transaction failed: " . $e->getMessage());
    }
    
    } else {
        // If no tours in the session
        echo "No tours in the shopping cart.";
    }
} else {
    header("Location: shopping.php");
    exit();
}

mysqli_close($conn);
?>
