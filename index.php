<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>John's Restaurant - Home</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">John's Restaurant</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                        <?php if(!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <?php if(!isAdmin()): ?>
                                <li class="nav-item"><a class="nav-link" href="transaction.php">Checkout</a></li>
                            <?php endif; ?>

                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section with background image -->
        <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80');">
            <div class="overlay">
                <h1 class="display-4">Welcome to John's Restaurant</h1>

                <h3>Your Destination for Flavor &amp; Comfort</h3>

                <a href="menu.php" class="btn btn-warning mt-3">View Our Menu</a>
            </div>
        </section>
    </body>
</html>