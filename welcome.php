<?php
    // Start session.
    session_start();

    // If the user is not logged in, redirect to login page.
    if (!isset($_SESSION["email"])) {
        header("Location: login.php");
        exit();
    }

    // Redirect to home page after 5 seconds.
    header("refresh: 3; url = home.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Successful login!</title>
    </head>

    <body>
        <h2>Welcome!</h2>

        <p>You have successfully logged in.</p>
        <p>Redirecting to home page...</p>
    </body>
</html>