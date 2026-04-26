<?php
    // Start session.
    session_start();

    // If the user is not logged in, redirect to login page.
    if (!isset($_SESSION["email"])) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>John's Restaurant - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="home.php">John's Restaurant</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">


          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>

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

            <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="overlay">
        <h1 class="display-4">Welcome to John's Restaurant</h1>
        <h3>Your Destination for Flavor & Comfort</h3>
        <a href="menu.php" class="btn btn-warning mt-3">View Our Menu</a>
      </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>