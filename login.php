<?php
    // Start session
    session_start();

    // If the user is already logged in, redirect to home page
    if (isset($_SESSION["username"])) {
        header("Location: home.php");
        exit();
    }

    // Check if the error parameter is set in the URL
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<p style="color: red;">Incorrect username or password. Please try again.</p>';
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>

    <body>
        <h2>Login Page</h2>

        <form action="authentication.php" method="post">
            <label for="uname">User Name:</label>
            <input type="text" id="uname" name="uname" value=""><br>

            <label for="psw">Password:</label>
            <input type="password" id="psw" name="psw" value=""><br>

            <input type="submit" name="login" value="Submit">
        </form>

        <p>Don't have an account? <a href="registration.php">Register here!</a></p>
    </body>
</html>