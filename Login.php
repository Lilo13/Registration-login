<?php
  // Initialize sessions
  session_start();

  // Check if the user is already logged in, if yes then redirect him to the welcome page
  if(isset($_SESSION["loggedin"]) === "POST"){
    header("location: Welcome.php");
    exit;
  }

  // Include config file
  require_once "config.php";

  // Define variables and initialize with empty values
  $username = $password = '';
  $username_err = $password_err = '';
  $conn->set_charset("utf8");

  // Process submitted form data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if username is empty
    if(empty(trim($_POST['username']))){
      $username_err = 'Please enter username.';
    } else{
      $username = trim($_POST['username']);
    }


    // Check if password is empty
    if(empty(trim($_POST['password']))){
      $password_err = 'Please enter your password.';
    } else{
      $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
      // Prepare a select statement
      $sql = 'SELECT id, username, password FROM personal_info WHERE username = ?';

      if ($stmt = $conn->prepare($sql)) {

        // Set parameter
        $param_username = $username;

        // Bind param to statement
        $stmt->bind_param('s', $param_username);


        // Attempt to execute
        if ($stmt->execute()) {

          // Store result
          $stmt->store_result();

          // Check if username exists. Verify user exists then verify
          if ($stmt->num_rows == 1) {
            // Bind result into variables
            $stmt->bind_result($id, $username, $stored_password);

            if ($stmt->fetch()) {
             
              // Debugging
              echo "Entered Username: " . $username . "<br>";
              echo "Entered Password: " . $password . "<br>";
              echo "Stored Password: " . $stored_password . "<br>";
             
              // Compare passwords
              if ($password === $stored_password) {

                // Start a new session
                session_start();

                // Store data in sessions
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;

                // Redirect to user to page
                header('location: Welcome.php');
              } else {
                // Display an error for password mismatch
                $password_err = 'Invalid password';
              }
            }
          } else {
            $username_err = "Username does not exist.";
          }
        } else {
          echo "Oops! Something went wrong. Please try again.";

        }

        // Close statement
        $stmt->close();
      }

      // Close connection
      $conn->close();
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign in</title>
  <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
  <style>
    .wrapper{
      width: 500px;
      padding: 20px;
    }
    .wrapper h2 {text-align: center}
    .wrapper form .form-group span {color: red;}
  </style>
</head>
<body>
  <main>
    <section class="container wrapper">
      <h2 class="display-4 pt-3">Login</h2>
          <p class="text-center">Please fill this form to create an account.</p>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has_error':'';?>">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
              <span class="help-block"><?php echo $username_err;?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err))?'has_error':'';?>">
              <label for="password">Password</label>
              <input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
              <span class="help-block"><?php echo $password_err;?></span>
            </div>


            <div class="form-group">
              <input type="submit" class="btn btn-block btn-outline-primary" value="login">
            </div>
            <p>Don't have an account? <a href="Registration.php">Register here.</a>.</p>
          </form>
    </section>
  </main>
</body>
</html>