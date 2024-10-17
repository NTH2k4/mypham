<?php

include '../partials/header.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

$user_id = $_SESSION['id_user'];
$success_msg = '';
$error_msg = '';

$active_section = 'info';

if (isset($_POST['section'])) {
    $active_section = $_POST['section'];
} elseif (isset($_GET['section'])) {
    $active_section = $_GET['section'];
}

$sql = "SELECT username, fullname, email, phone, address, password FROM user WHERE id_user = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_info'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql_check = "SELECT * FROM user WHERE (email = :email OR phone = :phone) AND id_user != :user_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email, 'phone' => $phone, 'user_id' => $user_id]);

    if ($stmt_check->rowCount() > 0) {
        $error_msg = "Email or phone number already exists for another user.";
        $active_section = 'info';
    } else {
        $sql_update = "UPDATE user SET fullname = :fullname, email = :email, phone = :phone, address = :address WHERE id_user = :user_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(['fullname' => $fullname, 'email' => $email, 'phone' => $phone, 'address' => $address, 'user_id' => $user_id]);

        $success_msg = "Information updated successfully!";
        echo "<script>
            alert('$success_msg');
            window.location.href = '?section=info';
        </script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($old_password !== $user_data['password']) {
        $error_msg = "Old password is incorrect!";
        $active_section = 'password';
    } elseif (strlen($new_password) < 5) {
        $error_msg = "New password must be at least 5 characters long!";
        $active_section = 'password';
    } elseif ($new_password !== $confirm_password) {
        $error_msg = "New passwords do not match!";
        $active_section = 'password';
    } else {
        $sql_password = "UPDATE user SET password = :password WHERE id_user = :user_id";
        $stmt_password = $pdo->prepare($sql_password);
        $stmt_password->execute(['password' => $new_password, 'user_id' => $user_id]);

        $success_msg = "Password changed successfully!";
        echo "<script>
            alert('$success_msg');
            window.location.href = '?section=password';
        </script>";
        exit;
    }
}

if ($active_section === 'orders') {
    $sql_orders = "
        SELECT orders.id_order, orders.order_date, orders.total_price, orders.status, order_detail.product_name, 
               order_detail.price_at_purchase, order_detail.quantity
        FROM orders
        JOIN order_detail ON orders.id_order = order_detail.order_id
        WHERE orders.user_username = :user_username
        ORDER BY orders.order_date DESC";

    $stmt_orders = $pdo->prepare($sql_orders);
    $stmt_orders->execute(['user_username' => $user_data['username']]);
    $order_history = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
}

?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/account.css">
<style>
    /* Global Styles */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        padding-top: 50px;
        padding-bottom: 50px;
    }

    .tab-navigation {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .tab-navigation a {
        width: 30%;
        padding: 15px;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        border-radius: 50px;
        border: 2px solid transparent;
        transition: 0.3s ease;
        color: rgb(48,120,156);
    }

    .tab-navigation a.active {
        background-color: rgb(48,120,156);
        color: white;
        border-color: rgb(48,120,156);
    }

    .tab-navigation a:hover {
        background-color: rgba(48,120,156, 0.8);
        color: white;
        border-color: rgba(48,120,156, 0.8);
    }

    /* Card Layout */
    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 30px;
    }

    h2 {
        color: rgb(48,120,156);
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 16px;
    }

    .btn-custom {
        background-color: rgb(48,120,156);
        color: white;
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 16px;
        transition: 0.3s ease;
        display: block;
        width: 100%;
        margin-top: 20px;
    }

    .btn-custom:hover {
        background-color: rgba(48,120,156, 0.8);
        color: white;
    }

    /* Spacing and Layout Enhancements */
    .form-group {
        margin-bottom: 20px;
    }

    .table {
        margin-top: 30px;
    }

    /* Icons for a modern touch */
    .form-icon {
        color: rgb(48,120,156);
        margin-right: 10px;
    }

    /* Style alerts */
    .alert {
        padding: 15px;
        margin-bottom: 30px;
        border-radius: 8px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container">
    
    <div class="tab-navigation">
        <a href="?section=info" class="<?= ($active_section == 'info') ? 'active' : '' ?>">Information</a>
        <a href="?section=password" class="<?= ($active_section == 'password') ? 'active' : '' ?>">Password</a>
        <a href="?section=orders" class="<?= ($active_section == 'orders') ? 'active' : '' ?>">Order History</a>
    </div>

    <div class="card">
        
        <div id="account-info" style="<?= ($active_section == 'info') ? '' : 'display: none;' ?>">
            <h2><i class="fas fa-user-circle form-icon"></i> Account Information</h2>
            <?php if ($error_msg && !isset($_POST['change_password'])): ?>
                <div class="alert alert-danger"><?= $error_msg ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="section" value="info">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" value="<?= $user_data['username'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" name="fullname" id="fullname" value="<?= $user_data['fullname'] ?>" placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $user_data['email'] ?>" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="<?= $user_data['phone'] ?>" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?= $user_data['address'] ?>" placeholder="Enter your address">
                </div>

                <button type="submit" name="update_info" class="btn btn-custom">Update Info</button>
            </form>
        </div>

        
        <div id="password-section" style="<?= ($active_section == 'password') ? '' : 'display: none;' ?>">
            <h2><i class="fas fa-key form-icon"></i> Change Password</h2>
            <?php if ($error_msg && isset($_POST['change_password'])): ?>
                <div class="alert alert-danger"><?= $error_msg ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="section" value="password">

                <div class="form-group">
                    <label for="oldPassword">Old Password</label>
                    <input type="password" class="form-control" name="old_password" id="oldPassword" placeholder="Enter old password">
                </div>

                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="Enter new password">
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirmPassword" placeholder="Confirm new password">
                </div>

                <button type="submit" name="change_password" class="btn btn-custom">Change Password</button>
            </form>
        </div>

        
        <div id="order-history-section" style="<?= ($active_section == 'orders') ? '' : 'display: none;' ?>">
            <h2><i class="fas fa-shopping-cart form-icon"></i> Order History</h2>

            <?php if (empty($order_history)): ?>
                <p class="text-center">No orders found.</p>
            <?php else: ?>
                <?php
                $current_order_id = null;
                foreach ($order_history as $order): 
                    if ($current_order_id !== $order['id_order']): 
                        if ($current_order_id !== null): ?>
                            </tbody></table><br>
                        <?php endif;
                        $current_order_id = $order['id_order'];
                        ?>
                        
                        <h5>Order #<?= $order['id_order'] ?> (<?= $order['order_date'] ?>) - Status: <?= $order['status'] ?></h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price per 1</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                    <?php endif; ?>
                    <tr>
                        <td><?= $order['product_name'] ?></td>
                        <td><?= number_format($order['price_at_purchase'], 0, ',', '.') . ' ₫'; ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= number_format($order['price_at_purchase'] * $order['quantity'], 0, ',', '.') . ' ₫'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody></table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
