<?php
  // Start session.
  session_start();

  // If the user is already logged in, redirect to home page instead.
  if (isset($_SESSION["username"])) {
    header("Location: home.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John's Restaurant - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">John's Restaurant</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>

            <!-- Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Menu
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="menu.php">Full Menu</a></li>
                <li><a class="dropdown-item" href="specials.html">Specials</a></li>
              </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="transactions.php">Transactions</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
