<?php
    // Start session.
    session_start();

    // If the user is already logged in, redirect to home page.
    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit();
    }

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    $error_msg = "";
    $success = false;

    // Check if the form was submitted.
    if (isset($_POST["login"])) {

        // Pull the data from the form and sanitize it.
        $email = trim(addslashes($_POST["email"]));
        $passcode = trim(addslashes($_POST["psw"]));
        $passcode2 = trim(addslashes($_POST["psw2"]));
        

        // Checks whether required fields are empty.
        if (!empty($email) && !empty($passcode) && !empty($passcode2)) {

            // Checks that both password fields match.
            if ($passcode == $passcode2) {
                // Prepare the INSERT statement.
                $sql_string = "INSERT INTO users (email, pwd) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql_string);
                mysqli_stmt_bind_param($stmt, "ss", $email, $passcode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $success = true;
            } else {
                $error_msg = "Passwords do not match. Please try again.";
            }
        } else {
            $error_msg = "Please fill in all required fields.";
        }

        mysqli_close($conn);

        if ($success) {
            header("Location: login.php?registered=1");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John's Restaurant - Register</title>
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
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="overlay" style="width: 100%; max-width: 480px;">
            <h2 class="text-center mb-4">Create an Account</h2>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="registration.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email<span class="text-warning">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label for="psw" class="form-label">Password <span class="text-warning">*</span></label>
                    <input type="password" id="psw" name="psw" class="form-control" placeholder="Create a password" required>
                </div>

                <div class="mb-3">
                    <label for="psw2" class="form-label">Confirm Password <span class="text-warning">*</span></label>
                    <input type="password" id="psw2" name="psw2" class="form-control" placeholder="Re-enter your password" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" name="login" class="btn btn-warning btn-lg">Register</button>
                </div>
            </form>

            <p class="text-center mt-3 mb-0">
                Already have an account? <a href="login.php" class="text-warning">Login here!</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
