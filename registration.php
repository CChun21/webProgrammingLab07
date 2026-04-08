<!DOCTYPE html>
<html>
    <head>
        <title>Registration</title>
    </head>

    <body>
        <h2>Registration Page</h2>

        <form action="registration.php" method="post">
            <label for="uname">User Name:</label>
            <input type="text" id="uname" name="uname" value=""><br>

            <label for="psw">Password:</label>
            <input type="password" id="psw" name="psw" value=""><br>

            <label for="psw2">Reenter Password:</label>
            <input type="password" id="psw2" name="psw2" value=""><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value=""><br>

            <input type="submit" name="login" value="Submit">
        </form>
    </body>
</html>

<?php
    // Start session
    session_start();

    // If the user is already logged in, redirect to home page
    if (isset($_SESSION["username"])) {
        header("Location: home.php");
        exit();
    }

    // Link vars file and connect to DB
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    // Pull the data from the form and sanitize it
    $username = trim(addslashes($_POST["uname"]));
    $passcode = trim(addslashes($_POST["psw"]));
    $email = trim(addslashes($_POST["email"]));

    // Check if login button is clicked
    if (isset($_POST["login"])) {

        // Checks whether either of the three fields are empty
        // If all are filled, carry on to password check
        // If any of them are, print error message
        if (!empty($_POST["uname"]) && !empty($_POST["psw"]) && !empty($_POST["psw2"])) {
            
            // Checks that both password fields match
            // If they do, insert user into accounts table
            // If not, print error message
            if ($_POST["psw"] == $_POST["psw2"]) {
                $sql_string = "INSERT INTO accounts(username, password, email) VALUES ('$username','$passcode','$email')";
                mysqli_query($conn, $sql_string);
                echo "User data is registered! Go to the <a href='login.php'>login page</a> to log in.";
            } else {
                echo "Both Passwords dont match";
            }
        } else {
            echo "Missing username or password fix it <br>";
        }
    } else {
        echo "Waiting for entry...";
    }

    // Close the connection
    mysqli_close($conn);
?>