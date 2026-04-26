<?php
    // Start session.
    session_start();

    // If the user is already logged in, redirect to home page.
    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John's Restaurant - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-bg">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">John's Restaurant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Menu</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="menu.php">Full Menu</a></li>
                            <li><a class="dropdown-item" href="specials.html">Specials</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="overlay" style="width: 100%; max-width: 440px;">
            <h2 class="text-center mb-4">Login</h2>

            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger" role="alert">
                    Incorrect username or password. Please try again.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
                <div class="alert alert-success" role="alert">
                    Registration successful! Please log in.
                </div>
            <?php endif; ?>

            <form action="authentication.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="psw" class="form-label">Password</label>
                    <input type="password" id="psw" name="psw" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" name="login" class="btn btn-warning btn-lg">Login</button>
                </div>
            </form>

            <p class="text-center mt-3 mb-0">
                Don't have an account? <a href="registration.php" class="text-warning">Register here!</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
