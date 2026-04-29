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
        
        <title>John's Restaurant - Contact</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
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
                        <li class="nav-item"><a class="nav-link" href="transaction.php">Transaction</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                        
                        <?php if(!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80');">
            <div class="container overlay">
                <h1 class="text-center">Contact Us</h1>

                <h3 class="text-center mb-4">We’d Love to Hear From You</h3>

                <p>Email us at:
                <a href="mailto:info@johnrestaurant.com" class="text-warning">info@johnrestaurant.com</a>
                </p>

                <p>Phone: (555) 123-4567</p>

                <form>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control"></textarea>
                    </div>

                    <button class="btn btn-warning">Submit</button>
                </form>

                <a href="index.php" class="btn btn-light mt-3">Return Home</a>
            </div>
        </section>
    </body>
</html>