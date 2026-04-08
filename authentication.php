<?php
    // Start session
    session_start();

    // Link vars file and connect to DB
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    // Pull the data from the login form and sanitize it
    $username = trim(addslashes($_POST["uname"]));
    $passcode = trim(addslashes($_POST["psw"]));

    $sql_string = "SELECT * FROM accounts WHERE username='$username' AND password='$passcode'";
    $result = mysqli_query($conn, $sql_string);

    if (mysqli_num_rows($result) >= 1) {
        $_SESSION["username"] = $username;
        header("Location: welcome.php");
    } else {
        header("Location: login.php?error=1");
    }
    // Close the connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Authorizing...</title>
    </head>
</html>