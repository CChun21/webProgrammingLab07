<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    if (!isLoggedIn()) {
        header("Location: login.php?error=not_logged_in");
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $cart = &$_SESSION['cart'];

    $TAX_RATE  = 0.085;
    $subtotal = array_sum(array_map(function($e) {
        return $e['price'] * $e['qty'];
    }, $cart));
    
    $tax = $subtotal * $TAX_RATE;
    $total = $subtotal + $tax;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_to_cart'])) {
            $item_id = (int)$_POST['item_id'];
            $item_name = $_POST['item_name'];
            $item_price = (float)$_POST['item_price'];
            $found = false;
            foreach ($cart as &$entry) {
                if ($entry['id'] == $item_id) {
                    $entry['qty']++;
                    $found = true;
                    break;
                }
            }

            unset($entry);
            if (!$found) {
                $cart[] = [
                    'id' => $item_id,
                    'name' => $item_name,
                    'price' => $item_price,
                    'qty' => 1,
                ];
            }

            header('Location: transaction.php');
            exit();
        }

        if (isset($_POST['remove_one'])) {
            $item_id = (int)$_POST['item_id'];
            foreach ($cart as $key => &$entry) {
                if ($entry['id'] == $item_id) {
                    $entry['qty']--;
                    if ($entry['qty'] <= 0) {
                        unset($cart[$key]);
                    }
                    break;
                }
            }
            $_SESSION['cart'] = array_values($cart);
            header('Location: transaction.php');
            exit();
        }
    
        if (isset($_POST['clear_cart'])) {
            $_SESSION['cart'] = [];
            header('Location: transaction.php');
            exit();
        }

        // Place Order logic
        if (isset($_POST['place_order']) && !empty($cart)) {
            $user_id = $_SESSION['user_id'];

            // Insert the main transaction record first
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, total) VALUES (?, ?)");
            $stmt->bind_param("id", $user_id, $total);
            
            if ($stmt->execute()) {
                // Capture the auto-incremented trans_id created by the database
                $new_trans_id = $conn->insert_id;

                // Prepare the statement for the transaction_items table
                $item_stmt = $conn->prepare("INSERT INTO transaction_items (trans_id, menu_id, quantity) VALUES (?, ?, ?)");

                // Loop through each item in the session cart
                foreach ($cart as $item) {
                    $menu_id = $item['id'];
                    $qty = $item['qty'];
                    
                    // Bind and execute for every item in the cart
                    $item_stmt->bind_param("iii", $new_trans_id, $menu_id, $qty);
                    $item_stmt->execute();
                }

                // Clean up and redirect
                $_SESSION['cart'] = [];
                header("Location: transaction.php?status=success");
                exit();
            } else {
                echo "Error creating transaction: " . $conn->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>John's Restaurant - Current Transaction</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            .transaction-table th {
                background: rgba(255, 193, 7, 0.15);
                color: #ffc107;
                border-bottom: 2px solid #ffc107;
            }
            .transaction-table td {
                vertical-align: middle;
                color: #000000;
                border-color: rgba(255,255,255,0.1);
            }
            .transaction-table tbody tr:hover {
                background: rgba(255,255,255,0.05);
            }
            .totals-card {
                background: rgb(0,0,0);
                border: 1px solid rgba(255,193,7,0.35);
                border-radius: 10px;
                padding: 24px 28px;
            }
            .totals-card .label {
                color: #bbb;
            }
            .totals-card .value {
                color: #fff;
                font-weight: 600;
            }
            .totals-card .total-row .label,
            .totals-card .total-row .value {
                color: #ffc107;
                font-size: 1.2rem;
            }
            .qty-badge {
                display: inline-block;
                min-width: 30px;
                text-align: center;
                background: rgba(255,193,7,0.2);
                border: 1px solid #ffc107;
                border-radius: 6px;
                color: #ffc107;
                font-weight: 700;
                padding: 2px 8px;
            }
            .btn-qty {
                padding: 2px 9px;
                font-size: 0.85rem;
            }
            .empty-cart-icon {
                font-size: 4rem;
                opacity: 0.25;
            }
        </style>
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
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        <li class="nav-item"><a class="nav-link active" href="transaction.php">Checkout</a></li>
                        <li class="nav-item"><a class="nav-link" href="accountPage.php">My Account</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="page-bg">
            <div class="container py-4">
                <div class="overlay">
                    <h1 class="text-center mb-1">Current Transaction</h1>
                    <h5 class="text-center text-warning mb-4">Your Order Summary</h5>

                    <?php if (empty($cart)): ?>
                        <!-- Empty state -->
                        <div class="text-center py-5">
                            <div class="empty-cart-icon">🛒</div>
                            <p class="mt-3">Your cart is empty.</p>
                            <a href="menu.php" class="btn btn-warning mt-2">Browse the Menu</a>
                        </div>

                    <?php else: ?>
                        <!-- Cart table -->
                        <div class="table-responsive">
                            <table class="table transaction-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Line Total</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $entry): ?>
                                        <tr>
                                            <td><?php echo ucwords(htmlspecialchars($entry['name'])); ?></td>
                                            <td class="text-center">$<?php echo number_format($entry['price'], 2); ?></td>
                                            <td class="text-center">
                                                <span class="qty-badge"><?php echo $entry['qty']; ?></span>
                                            </td>
                                            <td class="text-center">$<?php echo number_format($entry['price'] * $entry['qty'], 2); ?></td>
                                            <td class="text-center">
                                                <!-- Increment -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="item_id"    value="<?php echo $entry['id']; ?>">
                                                    <input type="hidden" name="item_name"  value="<?php echo htmlspecialchars($entry['name']); ?>">
                                                    <input type="hidden" name="item_price" value="<?php echo $entry['price']; ?>">
                                                    <button type="submit" name="add_to_cart" class="btn btn-success btn-qty">+</button>
                                                </form>

                                                <!-- Decrement -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="item_id" value="<?php echo $entry['id']; ?>">
                                                    <button type="submit" name="remove_one" class="btn btn-secondary btn-qty">−</button>
                                                </form>

                                                <!-- Remove line -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="item_id" value="<?php echo $entry['id']; ?>">
                                                    <button type="submit" name="remove_item" class="btn btn-danger btn-qty">✕</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="row justify-content-end mt-3">
                            <div class="col-md-5">
                                <div class="totals-card">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="label">Subtotal (pre-tax)</span>
                                        <span class="value">$<?php echo number_format($subtotal, 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="label">Tax (8.5%)</span>
                                        <span class="value">$<?php echo number_format($tax, 2); ?></span>
                                    </div>
                                    <hr style="border-color:rgba(255,193,7,0.3);">
                                    <div class="d-flex justify-content-between total-row">
                                        <span class="label">Total</span>
                                        <span class="value">$<?php echo number_format($total, 2); ?></span>
                                    </div>
                                </div>

                                <!-- Clear cart -->
                                <form method="POST" class="mt-3 text-end">
                                    <button type="submit" name="clear_cart"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Clear your entire transaction?');">
                                        Clear Transaction
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Place Order area-->
                    <div class="mt-4">
                        <?php if (!empty($cart)): ?>
                            <form method="POST">
                                <button type="submit" name="place_order" class="btn btn-warning w-100 fw-bold py-2">
                                    Confirm & Place Order
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 py-2" disabled>
                                Add items to your cart to place an order
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                        <div class="alert alert-success mt-3 text-center">
                            Order placed successfully!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </body>
</html>