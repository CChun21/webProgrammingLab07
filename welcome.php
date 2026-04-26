<?php
    // Start session.
    session_start();

    // If the user is not logged in, redirect to login page.
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    // Redirect to home page after 3 seconds.
    header("refresh: 3; url=home.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John's Restaurant - Welcome!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-bg">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">John's Restaurant</a>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="overlay text-center">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
            <p>You have successfully logged in.</p>
            <p class="text-warning">Redirecting to home page in 3 seconds...</p>
            <a href="home.php" class="btn btn-warning mt-2">Go Now</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
