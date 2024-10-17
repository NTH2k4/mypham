<?php
include '../partials/header.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (!isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

$sql_stats = "
    SELECT 
        SUM(total_price) AS total_profit,
        SUM(CASE WHEN status = 'Pending' THEN total_price ELSE 0 END) AS pending_profit,
        (SELECT COUNT(*) FROM product) AS total_products,
        (SELECT COUNT(*) FROM user) AS total_users
    FROM orders";
$stmt_stats = $pdo->prepare($sql_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
?>


<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-2 text-white p-4" style="background-color: rgb(48,120,156);">
            <h3 class="mb-4">Trang Chủ Quản Trị</h3>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="?section=dashboard" class="nav-link text-white">📊 Tổng Quan</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="?section=category" class="nav-link text-white">📁 Quản Lý Danh Mục</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="?section=products" class="nav-link text-white">🛍️ Quản Lý Sản Phẩm</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="?section=users" class="nav-link text-white">👥 Quản Lý Người Dùng</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="?section=orders" class="nav-link text-white">🧾 Quản lý Đơn Hàng</a>
                </li>
            </ul>
        </div>

        
        <div class="col-lg-10 p-5">
            
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary">💵 Tổng lợi nhuận</h5>
                            <p class="card-text"><?= number_format($stats['total_profit'], 0, ',', '.') . ' ₫'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-warning">⏳ Lợi nhuận chờ xử lý</h5>
                            <p class="card-text"><?= number_format($stats['pending_profit'], 0, ',', '.') . ' ₫'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success">📦 Tổng số sản phẩm</h5>
                            <p class="card-text"><?= $stats['total_products']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-info">👥 Tổng số người dùng</h5>
                            <p class="card-text"><?= $stats['total_users']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php
            $active_section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

            if ($active_section == 'category') {
                include 'admin_categories.php';
            } elseif ($active_section == 'products') {
                include 'admin_products.php';
            } elseif ($active_section == 'users') {
                include 'admin_users.php';
            } elseif ($active_section == 'orders') {
                include 'admin_orders.php';
            } else {
                echo '<h3>Welcome to the Admin Dashboard!</h3>';
            }
            ?>
        </div>
    </div>
</div>

        </body>
        </html>
