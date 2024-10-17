<?php
session_start();
define('BASE_URL', '/mypham/');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'plushy';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

$sql_categories = "SELECT * FROM category";
$stmt_categories = $pdo->prepare($sql_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

$total_quantity = 0;

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    $sql_cart = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_username = :username";
    $stmt_cart = $pdo->prepare($sql_cart);
    $stmt_cart->execute(['username' => $_SESSION['username']]);
    $cart_data = $stmt_cart->fetch(PDO::FETCH_ASSOC);

    if ($cart_data['total_quantity']) {
        $total_quantity = $cart_data['total_quantity'];
    }
}

function isAdmin($pdo, $user_id)
{
    $sql = "SELECT isAdmin FROM user WHERE id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_user' => $user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user_data && $user_data['isAdmin'] == 1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mỹ Phẩm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/index.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/header.css">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(48,120,156); padding: 20px 40px; position: relative;">
        <div class="container-fluid">
            
        <a class="navbar-brand me-5" href="<?php echo BASE_URL; ?>index.php">
                <img src="<?php echo BASE_URL; ?>assets/images/logolinhnga2.jpg" alt="Plushy Logo" style="border-radius: 5px; height: 60px;">
                </a>

            
            <div class="dropdown me-5">
                
                <a class="btn btn-outline-light dropdown-toggle text-white fw-semibold rounded-pill" href="#" role="button" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Thể Loại
                </a>
                <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/shop.php?category_id=<?= $category['id_category']; ?>">
                                <?= $category['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                
                <ul class="navbar-nav">
                    <?php if (!isset($_SESSION['id_user']) || !isAdmin($pdo, $_SESSION['id_user'])): ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link text-white fw-semibold" href="<?php echo BASE_URL; ?>pages/shop.php">Cửa Hàng</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link text-white fw-semibold" href="<?php echo BASE_URL; ?>pages/aboutus.php">Giới Thiệu</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link text-white fw-semibold" href="<?php echo BASE_URL; ?>pages/contact.php">Liên Hệ</a>
                        </li>
                    <?php endif; ?>
                </ul>

                
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <a class="text-white me-3 fw-bold" href="<?php echo BASE_URL; ?>pages/account.php">Hello,
                            <?php echo $_SESSION['username']; ?>! </a>
                        <a href="<?php echo BASE_URL; ?>controllers/logout.php" class="btn btn-light rounded-pill me-4">Logout</a>
                        <?php if (!isAdmin($pdo, $_SESSION['id_user'])): ?>
                            <a href="<?php echo BASE_URL; ?>pages/cart.php" class="btn btn-outline-light rounded-pill position-relative" style="font-size:15px">
                                🛒
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $total_quantity ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>pages/auth.php" class="btn btn-light rounded-pill me-4" style="font-size:15px">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>


