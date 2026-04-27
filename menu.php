<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');

    // ============================================================
    // MENU DATA ARRAY
    // This array will be replaced with a phpMyAdmin DB query later.
    // Structure: each category has a 'name' and an 'items' array.
    // Each item has a 'name' and a 'price'.
    // ============================================================
    
    $menu_categories = [
        [
            "name"  => "Starters",
            "items" => [
                ["name" => "Bruschetta",        "price" => 8],
                ["name" => "Stuffed Mushrooms", "price" => 9],
                ["name" => "Garlic Bread",      "price" => 6],
            ]
        ],
        [
            "name"  => "Main Courses",
            "items" => [
                ["name" => "Grilled Salmon",   "price" => 18],
                ["name" => "Steak & Fries",    "price" => 22],
                ["name" => "Chicken Alfredo",  "price" => 17],
            ]
        ],
        [
            "name"  => "Desserts",
            "items" => [
                ["name" => "Cheesecake",           "price" => 7],
                ["name" => "Tiramisu",             "price" => 8],
                ["name" => "Chocolate Lava Cake",  "price" => 9],
            ]
        ],
    ]
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>John's Restaurant - Menu</title>

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
                        <li class="nav-item"><a class="nav-link active" href="menu.php">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        
                        <?php if(!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80');"> 
            <div class="container overlay mt-5">
                <h1 class="text-center">Our Full Menu</h1>
                
                <h3 class="text-center mb-4">Delicious Meals Crafted Fresh</h3>

                <div class="row">
                    <?php foreach ($menu_categories as $category): ?>
                        <div class="col-md-4">
                            <h4><?php echo htmlspecialchars($category["name"]); ?></h4>
                            <ul class="list-unstyled">
                                <?php foreach ($category["items"] as $item): ?>
                                    <li>
                                        <?php echo htmlspecialchars($item["name"]); ?>
                                        &mdash;
                                        $<?php echo htmlspecialchars($item["price"]); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="index.php" class="btn btn-warning mt-3">Back Home</a>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>