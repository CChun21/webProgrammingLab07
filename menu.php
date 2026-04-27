<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Insertion logic.
        if (isset($_POST['add_item'])) {
            $type = strtolower($_POST['type']);
            $name = strtolower($_POST['name']);
            $description = strtolower($_POST['description']);
            $price = $_POST['price'];
            $allergens = $_POST['allergens'] ?? [];
            
            // Convert the given ingredients into an array.
            $ingredients_raw = explode(',', $_POST['ingredients']);
            $ingredients_array = array_map('strtolower', array_map('trim', $ingredients_raw));
            
            // Turn arrays into JSON strings.
            $ingredients_json_to_store = json_encode($ingredients_array);
            $allergens_json_to_store = json_encode($allergens);

            $stmt = $conn->prepare("INSERT INTO menu_items (type, name, description, price, ingredients, allergens) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $type, $name, $description, $price, $ingredients_json_to_store, $allergens_json_to_store);
            
            if($stmt->execute()) {
                header("Location: menu.php");
                exit();
            }
        }

        // Deletion logic.
        if (isset($_POST['delete_item'])) {
            $name_to_delete = $_POST['item_name'];
            
            $stmt = $conn->prepare("DELETE FROM menu_items WHERE name = ?");
            $stmt->bind_param("s", $name_to_delete);
            
            if ($stmt->execute()) {
                header("Location: menu.php?deleted=1");
                exit();
            }
        }
    }

    // Fetch all the items, ordered by category and name.
    $sql = "SELECT * FROM menu_items ORDER BY type ASC, name ASC";
    $result = mysqli_query($conn, $sql);

    $menu_data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $category = $row['type'];
        
        // Decode JSON strings back into PHP arrays
        $row['ingredients'] = json_decode($row['ingredients'], true) ?? [];
        $row['allergens'] = json_decode($row['allergens'], true) ?? [];
        
        // Group items by their type (Entree, Dessert, etc.)
        $menu_data[$category][] = $row;
    }
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
            <div class="container overlay">
                <h1 class="text-center pt-3">Our Full Menu</h1>
                <h3 class="text-center mb-1">Delicious Meals Crafted Fresh</h3>

                <div class="menu-scroll-container">
                    <div class="row">
                        <?php if (empty($menu_data)): ?>
                                <p class="text-center">Menu is still a work-in-progress, please come back later!</p>

                        <?php else: ?>
                            <?php foreach ($menu_data as $categoryName => $items): ?>
                                <div class="col-12 mt-4">
                                    <h2 class="border-bottom pb-2 text-warning"><?php echo ucfirst(htmlspecialchars($categoryName)); ?>s</h2>
                                </div>

                                <?php foreach ($items as $item): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 shadow-sm border-0">
                                            <div class="card-body text-dark">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title mb-1"><?php echo ucwords(htmlspecialchars($item['name'])); ?></h5>
                                                    <span class="badge text-dark">$<?php echo number_format($item['price']); ?></span>
                                                </div>
                                                
                                                <p class="card-text text-muted small italic mb-2">
                                                    <?php echo ucfirst(htmlspecialchars($item['description'])); ?>
                                                </p>

                                                <div class="mt-2">
                                                    <strong>Ingredients:</strong><br>
                                                    <small><?php echo implode(', ', array_map('ucfirst', $item['ingredients'])); ?></small>
                                                </div>

                                                <?php if (!empty($item['allergens'])): ?>
                                                    <div class="mt-2 text-danger">
                                                        <strong><small>Allergens:</small></strong>
                                                        <small><?php echo is_array($item['allergens']) ? implode(', ', $item['allergens']) : $item['allergens']; ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(isAdmin()): ?>
                    <!-- 'Add Item' button trigger modal -->
                    <button type="button" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                    Add Item
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLongTitle">Add an Item</h5>

                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body text-dark text-start">
                                    <form method="POST">
                                        <section>
                                            <label for="type" class="form-label">Category (Choose One):</label><br>
                                    
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="entree" value="entree" required>
                                                    <label class="form-check-label" for="entree">
                                                        Entree
                                                    </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="appetizer" value="appetizer">
                                                    <label class="form-check-label" for="appetizer">
                                                        Appetizer
                                                    </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="side" value="side">
                                                    <label class="form-check-label" for="side">
                                                        Side
                                                    </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="dessert" value="dessert">
                                                    <label class="form-check-label" for="dessert">
                                                        Dessert
                                                    </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="drink" value="drink">
                                                    <label class="form-check-label" for="drink">
                                                        Drink
                                                    </label>
                                            </div>
                                        </section>
                                        
                                        <hr>
                                        
                                        <label for="name" class="form-label">Item Name</label><br>
                                        <input type="text" name="name" placeholder="Name of Item" required><br>
                                        
                                        <hr>

                                        <label for="description" class="form-label">Description of Item</label><br>
                                        <textarea name="description" placeholder="e.g. A hot delicacy!" required></textarea><br>

                                        <hr>

                                        <label for="price" class="form-label">Price</label><br>
                                        <input type="number" name="price" placeholder="As an integer." required><br>
                                        
                                        <hr>

                                        <label for="ingredients" class="form-label">Ingredients</label><br>
                                        <textarea name="ingredients" placeholder="e.g. Eggs, Bacon, Milk" required></textarea><br>
                                        
                                        <hr>
                                    
                                        <section>
                                            <label for="allergens" class="form-label">Allergens (Of the "Big 9")</label><br>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="milk" value="milk">
                                                <label class="form-check-label" for="milk">
                                                    Milk
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="eggs" value="eggs">
                                                <label class="form-check-label" for="eggs">
                                                    Eggs
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="fish" value="fish">
                                                <label class="form-check-label" for="fish">
                                                    Fish
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="shellfish" value="shellfish">
                                                <label class="form-check-label" for="shellfish">
                                                    Shellfish
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="tree nuts" value="tree nuts">
                                                <label class="form-check-label" for="tree nuts">
                                                    Tree Nuts
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="peanuts" value="peanuts">
                                                <label class="form-check-label" for="peanuts">
                                                    Peanuts
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="wheat" value="wheat">
                                                <label class="form-check-label" for="wheat">
                                                    Wheat
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="soybeans" value="soybeans">
                                                <label class="form-check-label" for="soybeans">
                                                    Soybeans
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="allergens[]" id="sesame" value="sesame">
                                                <label class="form-check-label" for="sesame">
                                                    Sesame
                                                </label>
                                            </div>
                                        </section>

                                        <hr>

                                        <button type="submit" name="add_item">Add to Menu</button>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 'Remove Item' button trigger modal -->
                    <button type="button" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Remove Item
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header text-dark">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body text-dark">
                                    <h5 class="text-danger">Remove an Item</h5>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Select Item to Delete</label>
                                            <select name="item_name" class="form-select" required>
                                                <option value="" selected disabled>Choose an item...</option>
                                                <?php 
                                                // Loop through menu data to generate list of available entries.
                                                foreach ($menu_data as $category => $items) {
                                                    foreach ($items as $item) {
                                                        echo '<option value="' . htmlspecialchars($item['name']) . '">' . 
                                                            ucwords(htmlspecialchars($item['name'])) . ' (' . ucfirst($category) . ')</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="delete_item" class="btn btn-danger w-100">Delete Item</button>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>