<?php
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
];
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

<body class="page-bg">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">John's Restaurant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Menu</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
