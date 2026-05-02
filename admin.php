<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');

    // Check if the user is logged in.
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    $sql = "SELECT trans_id, user_id, total, `date-time` FROM transactions ORDER BY `date-time` ASC";
    $order_query = $conn->prepare($sql);

    if ($order_query === false) {
        die("SQL Error: " . $conn->error);
    }

    $order_query->execute();
    $order_result = $order_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>John's Restaurant - Admin Page</title>
        
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
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                        <?php if(!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <?php if(!isAdmin()): ?>
                                <li class="nav-item"><a class="nav-link" href="transaction.php">Checkout</a></li>
                                <li class="nav-item"><a class="nav-link" href="accountPage.php">My Account</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link active" href="admin.php">Admin Page</a></li>
                            <?php endif; ?>
                            
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1600&q=80');">
            <div class="container overlay">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-warning border-bottom pb-2">Transaction List</h2>
                        
                        <?php if ($order_result->num_rows > 0): ?>
                            <div class="table-responsive mt-4">
                                <table class="table table-dark table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>User ID</th>
                                            <th>Total Spent</th>
                                            <th>Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $order_result->fetch_assoc()): ?>
                                            <tr>
                                                <td>#<?php echo $row['trans_id']; ?></td>
                                                <td>#<?php echo $row['user_id']; ?></td>
                                                <td>$<?php echo number_format($row['total'], 2); ?></td>
                                                <td><?php echo date('M d, Y - h:i A', strtotime($row['date-time'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mt-4">
                                You haven't placed any orders yet!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>