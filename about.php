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

        <title>John's Restaurant - About Us</title>

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
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                        <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                        <?php if(!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <?php if(!isAdmin()): ?>
                                <li class="nav-item"><a class="nav-link" href="transaction.php">Checkout</a></li>
<<<<<<< HEAD
                                <li class="nav-item"><a class="nav-link" href="history.php">My Orders</a></li>
                                <li class="nav-item"><a class="nav-link" href="accountPage.php">My Account</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link" href="admin.php">Admin Page</a></li>
                                <li class="nav-item"><a class="nav-link" href="history.php">All Orders</a></li>
=======
                                <li class="nav-item"><a class="nav-link" href="accountPage.php">My Account</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link" href="admin.php">Admin Page</a></li>
>>>>>>> 34fb071801fdcc4033be34e03b9460f28b4ae84c
                            <?php endif; ?>
                            
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80');">
            <div class="container overlay">
                <h1 class="text-center">About John's Restaurant</h1>

                <p>
                Founded in 2025, John's Restaurant brings comfort food with a modern twist.
                Our mission is to create memorable dining experiences through quality ingredients and exceptional service.
                </p>

                <p>We believe food brings people together.</p>

                <a href="contact.php" class="btn btn-warning mt-3">Contact Us</a>
            </div>
        </section>
    </body>
</html>