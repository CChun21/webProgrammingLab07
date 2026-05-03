<?php
    // Start session.
    session_start();

    // User state helper file.
    include('userState.php');

    // Redirect if not logged in.
    if (!isLoggedIn()) {
        header("Location: login.php?error=not_logged_in");
        exit();
    }

    // Link vars file and connect to DB.
    include('/home/hah1049/PHP-Includes/php-vars.inc');
    $conn = mysqli_connect($db_server, $user, $password, $db_names);

    $user_id = $_SESSION['user_id'];

    // -------------------------------------------------------
    // Fetch transactions — all rows for admin, own rows for customer
    // -------------------------------------------------------
    if (isAdmin()) {
        // Admins see every transaction, joined with the user's email
        $sql = "
            SELECT t.trans_id, t.user_id, u.email, t.total, t.`date-time`
            FROM transactions t
            JOIN users u ON u.user_ID = t.user_id
            ORDER BY t.`date-time` DESC
        ";
        $stmt = $conn->prepare($sql);
    } else {
        // Customers see only their own
        $sql = "
            SELECT t.trans_id, t.user_id, t.total, t.`date-time`
            FROM transactions t
            WHERE t.user_id = ?
            ORDER BY t.`date-time` DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    }

    $stmt->execute();
    $transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // -------------------------------------------------------
    // For each transaction, fetch its line items
    // -------------------------------------------------------
    $item_stmt = $conn->prepare("
        SELECT m.name, m.price, ti.quantity
        FROM transaction_items ti
        JOIN menu_items m ON m.id = ti.menu_id
        WHERE ti.trans_id = ?
        ORDER BY m.name
    ");

    $transaction_items = [];
    foreach ($transactions as $tx) {
        $item_stmt->bind_param("i", $tx['trans_id']);
        $item_stmt->execute();
        $transaction_items[$tx['trans_id']] = $item_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>John's Restaurant - Transaction History</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            .history-heading {
                color: #ffc107;
                border-bottom: 2px solid rgba(255,193,7,0.4);
                padding-bottom: 8px;
                margin-bottom: 24px;
            }
            .tx-card {
                background: rgba(0,0,0,0.65);
                border: 1px solid rgba(255,193,7,0.25);
                border-radius: 10px;
                margin-bottom: 18px;
                overflow: hidden;
            }
            .tx-card-header {
                background: rgba(255,193,7,0.12);
                border-bottom: 1px solid rgba(255,193,7,0.25);
                padding: 12px 18px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
                cursor: pointer;
            }
            .tx-card-header:hover {
                background: rgba(255,193,7,0.18);
            }
            .tx-id {
                color: #ffc107;
                font-weight: 700;
                font-size: 1rem;
            }
            .tx-meta {
                color: #ccc;
                font-size: 0.85rem;
            }
            .tx-total {
                color: #fff;
                font-weight: 600;
                font-size: 1rem;
            }
            .tx-date {
                color: #aaa;
                font-size: 0.82rem;
            }
            .tx-user-badge {
                background: rgba(255,255,255,0.1);
                color: #ddd;
                font-size: 0.78rem;
                padding: 2px 8px;
                border-radius: 99px;
                border: 1px solid rgba(255,255,255,0.15);
            }
            .tx-body {
                padding: 0 18px 14px;
            }
            .tx-items-table {
                width: 100%;
                font-size: 0.88rem;
                margin-top: 12px;
                border-collapse: collapse;
            }
            .tx-items-table th {
                color: #ffc107;
                font-weight: 500;
                border-bottom: 1px solid rgba(255,193,7,0.25);
                padding: 6px 10px;
                text-align: left;
            }
            .tx-items-table td {
                color: #ddd;
                padding: 6px 10px;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }
            .tx-items-table tr:last-child td {
                border-bottom: none;
            }
            .totals-mini {
                font-size: 0.85rem;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid rgba(255,193,7,0.2);
            }
            .totals-mini .row-lbl { color: #aaa; }
            .totals-mini .row-val { color: #fff; font-weight: 500; }
            .totals-mini .grand-lbl { color: #ffc107; }
            .totals-mini .grand-val { color: #ffc107; font-weight: 700; }
            .chevron {
                transition: transform 0.2s;
                color: #ffc107;
                font-size: 0.9rem;
            }
            .collapsed .chevron { transform: rotate(-90deg); }
            .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #888;
            }
            .empty-state .icon { font-size: 3.5rem; opacity: 0.3; }
            .admin-banner {
                background: rgba(220,53,69,0.15);
                border: 1px solid rgba(220,53,69,0.35);
                border-radius: 8px;
                color: #f08080;
                font-size: 0.85rem;
                padding: 8px 16px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
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

                        <?php if (!isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <?php else: ?>
                            <?php if (!isAdmin()): ?>
                                <li class="nav-item"><a class="nav-link" href="transaction.php">Checkout</a></li>
                                <li class="nav-item"><a class="nav-link active" href="history.php">My Orders</a></li>
                                <li class="nav-item"><a class="nav-link" href="accountPage.php">My Account</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link" href="admin.php">Admin Page</a></li>
                                <li class="nav-item"><a class="nav-link active" href="history.php">All Orders</a></li>
                            <?php endif; ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="page-bg">
            <div class="container py-4">
                <div class="overlay">

                    <?php if (isAdmin()): ?>
                        <h1 class="text-center mb-1">All Transactions</h1>
                        <h5 class="text-center text-warning mb-4">Admin View — Every Order</h5>
                        <div class="admin-banner">
                            🔒 Admin view — showing all customer transactions across the system.
                        </div>
                    <?php else: ?>
                        <h1 class="text-center mb-1">My Order History</h1>
                        <h5 class="text-center text-warning mb-4">Your previous orders</h5>
                    <?php endif; ?>

                    <?php if (empty($transactions)): ?>
                        <div class="empty-state">
                            <div class="icon">🧾</div>
                            <p class="mt-3">
                                <?php echo isAdmin() ? "No transactions found in the system." : "You haven't placed any orders yet."; ?>
                            </p>
                            <?php if (!isAdmin()): ?>
                                <a href="menu.php" class="btn btn-warning mt-2">Browse the Menu</a>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
                        <p class="text-muted mb-3" style="font-size:0.88rem;">
                            <?php echo count($transactions); ?> transaction<?php echo count($transactions) !== 1 ? 's' : ''; ?> found.
                            Click any order to expand its details.
                        </p>

                        <?php foreach ($transactions as $tx):
                            $TAX_RATE = 0.085;
                            $grand    = (float)$tx['total'];
                            $pretax   = $grand / (1 + $TAX_RATE);
                            $tax      = $grand - $pretax;
                            $items    = $transaction_items[$tx['trans_id']] ?? [];
                            $collapse_id = "tx-" . $tx['trans_id'];
                        ?>
                            <div class="tx-card">
                                <div class="tx-card-header collapsed"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#<?php echo $collapse_id; ?>"
                                     aria-expanded="false">

                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <span class="tx-id">Order #<?php echo $tx['trans_id']; ?></span>

                                        <?php if (isAdmin()): ?>
                                            <span class="tx-user-badge">
                                                <?php echo htmlspecialchars($tx['email']); ?>
                                            </span>
                                        <?php endif; ?>

                                        <span class="tx-date">
                                            <?php echo date('M d, Y — g:i A', strtotime($tx['date-time'])); ?>
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <span class="tx-total">$<?php echo number_format($grand, 2); ?></span>
                                        <span class="chevron">▼</span>
                                    </div>
                                </div>

                                <div class="collapse" id="<?php echo $collapse_id; ?>">
                                    <div class="tx-body">
                                        <?php if (!empty($items)): ?>
                                            <table class="tx-items-table">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Unit Price</th>
                                                        <th>Qty</th>
                                                        <th>Line Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($items as $item): ?>
                                                        <tr>
                                                            <td><?php echo ucwords(htmlspecialchars($item['name'])); ?></td>
                                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                            <td><?php echo $item['quantity']; ?></td>
                                                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <p class="text-muted mt-3" style="font-size:0.85rem;">No item details available.</p>
                                        <?php endif; ?>

                                        <!-- Totals breakdown -->
                                        <div class="totals-mini d-flex flex-column align-items-end">
                                            <div class="d-flex justify-content-between w-100" style="max-width:260px;">
                                                <span class="row-lbl">Subtotal (pre-tax)</span>
                                                <span class="row-val">$<?php echo number_format($pretax, 2); ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between w-100 mt-1" style="max-width:260px;">
                                                <span class="row-lbl">Tax (8.5%)</span>
                                                <span class="row-val">$<?php echo number_format($tax, 2); ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between w-100 mt-1" style="max-width:260px;">
                                                <span class="grand-lbl">Total</span>
                                                <span class="grand-val">$<?php echo number_format($grand, 2); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    </body>
</html>
