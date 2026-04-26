<?php
    // Start session.
    session_start();

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    // Pull the data from the login form and sanitize it.
    $email = trim(addslashes($_POST["email"]));
    $passcode = trim(addslashes($_POST["psw"]));

    // Create the SQL query template with placeholder ?s.
    $sql_string = "SELECT * FROM users WHERE email=? AND pwd=?";

    // Prepare the query statement.
    $stmt = mysqli_prepare($conn, $sql_string);

    // Bind the variables to the placeholders in sql_string, where the ss denotes that both variables are strings.
    mysqli_stmt_bind_param($stmt, "ss", $email, $passcode);

    // Execute the query.
    mysqli_stmt_execute($stmt);

    // Save the result.
    $result = mysqli_stmt_get_result($stmt);

    // If the number of rows in the result is equal to 1, then the user is authenticated and we can start a session for them.
    if (mysqli_num_rows($result) == 1) {
        $_SESSION["email"] = $email;
        $_SESSION["role"] = mysqli_fetch_assoc($result)["is_admin"] ? "admin" : "customer";
        header("Location: welcome.php");
    } else {
        header("Location: login.php?error=1");
    }

    // Close the connection and statement.
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Authorizing...</title>
    </head>
</html>