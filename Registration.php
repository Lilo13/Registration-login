<!DOCTYPE HTML>
<html>
    <head> Fast Travel Agency</head>
    <body>

        <div>
            <?php
            if (isset($_POST['create'])){
                echo "User submitted.";
            }
            ?>
        </div>
		
        <p> Please fill in the sign up form to create an account.</p>
        <div>
        <form action="Registration.php" method="post">
            <div class="container">
                <hr>
                <label for="username"><b> Enter Names </b></label>
                <input type="text" placeholder="Enter Name" name="username" id="username" required>
                <br>
                    
                <label for="fname"><b> First Name </b></label>
                <input type="text" placeholder="First Name" name="fname" id="fname" required>
                <br>

                <label for="lname"><b> Last Name </b></label>
                <input type="text" placeholder="Last Name" name="lname" id="lname" required>
                <br>

                <label for="email"><b> Email </b></label>
                <input type="text" placeholder="Enter Email" name="email" id="email" required>
                <br>
        
                <label for="psw"><b> Password </b></label>
                <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
                <br>
        
                <label for="psw"><b> Repeate Password </b></label>
                <input type="password" placeholder="Repeate Password" name="psw-repeate" id="psw-repeate" required>
                <hr>

                <p> Thank you for registaring with Fast Travel Agency.</p>
                <button type="submit"  class="registerbtn"> Register</button>
            </div>

            <div class="container signin">
                <br>
                <p>Do you have an account already? <a href="#">Sign in</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>