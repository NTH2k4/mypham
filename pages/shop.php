<?php

include '../partials/header.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];
    if (isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

$category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;

if ($category_id > 0) {
    $sql_products = "SELECT product.*, category.name AS category_name
                     FROM product
                     INNER JOIN category
                     ON product.category_id = category.id_category
                     WHERE product.category_id = :category_id";
    $stmt_products = $pdo->prepare($sql_products);
    $stmt_products->bindParam(':category_id', $category_id, PDO::PARAM_INT);
} else {
    $sql_products = "SELECT product.*, category.name AS category_name
                     FROM product
                     INNER JOIN category
                     ON product.category_id = category.id_category";
    $stmt_products = $pdo->prepare($sql_products);
}

$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

$sql_categories = "SELECT * FROM category";
$stmt_categories = $pdo->prepare($sql_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .category-list {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .category-link {
        color: rgb(48, 120, 156);
        text-decoration: none;
    }
    .category-link:hover {
        text-decoration: underline;
    }
    .product-card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-img {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        height: 200px;
        object-fit: cover;
    }
    .product-title {
        color: rgb(48, 120, 156);
        font-weight: bold;
    }
    .btn-primary {
        background-color: rgb(48, 120, 156);
        border-color: rgb(48, 120, 156);
    }
    .btn-primary:hover {
        background-color: rgb(38, 100, 136);
        border-color: rgb(38, 100, 136);
    }
    .hero-banner {
        background-image: url('../assets/images/shop-banner.jpg');
        background-size: cover;
        background-position: center;
        color: #fff;
        text-align: center;
        padding: 100px 0;
        border-radius: 20px;
        margin-bottom: 40px;
    }
    .hero-title {
        font-size: 3rem;
        font-weight: bold;
        color: rgb(48, 120, 156);
    }
</style>

<div class="container mt-5">
    
    <div class="hero-banner p-1">
        <h1 class="hero-title">Khám Phá Bộ Sưu Tập Mỹ phẩm Và Nước Hoa Cao Cấp Của Chúng Tôi</h1>
        <p class="lead" style="color:black" >
        Tìm sản phẩm làm đẹp hoàn hảo của bạn từ bộ sưu tập đa dạng mỹ phẩm và nước hoa của chúng tôi. 
        Hãy khám phá và nâng tầm vẻ đẹp tự nhiên của bạn với những sản phẩm chất lượng cao tại Linh Nga Shop.</p>
    </div>

    <div class="row">
        
        <div class="col-lg-3 col-md-4 col-12">
            <div class="category-list">
                <h3 class="mb-4">Thể Loại</h3>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="<?php echo BASE_URL; ?>pages/shop.php" class="category-link">Tất Cả Sản Phẩm</a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item">
                            <a href="<?php echo BASE_URL; ?>pages/shop.php?category_id=<?= $category['id_category']; ?>" class="category-link">
                                <?= $category['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        
        <div class="col-lg-9 col-md-8 col-12">
            <div id="products" class="row">
            <?php foreach ($products as $product): ?>
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4 d-flex">
        <div class="product-card w-100 d-flex flex-column">
            <img src="../<?= $product['image']; ?>" class="card-img-top product-img" alt="Product Image">
            <div class="card-body d-flex flex-column p-4 flex-grow-1">
                <p class="text-muted mb-1">Loại: <?= $product['category_name']; ?></p>
                <h5 class="product-title mb-3"><?= $product['name']; ?></h5>
                <p class="card-text fw-bold mb-3" style="font-size: 1.2rem;">
                    <?= number_format($product['price'], 0, ',', '.') . ' ₫'; ?>
                </p>
                <div class="mt-auto">
                    <button class="btn btn-primary w-100" onclick="window.location.href='details.php?id=<?= $product['id_product']; ?>'">Thông tin sản phẩm</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>