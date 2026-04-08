<?php
    // Start session
    session_start();

    // If the user is not logged in, redirect to login page
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    // Redirect to home page after 5 seconds
    header("refresh: 5; url = home.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Success!</title>
    </head>

    <body>
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>

        <p>You have successfully logged in.</p>
        <p>Redirecting to home page...</p>
    </body>
</html>