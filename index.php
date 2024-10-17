<?php
ob_start();
?>
<?php

include 'partials/header.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

$sql_new_arrivals = "SELECT product.*, category.name AS category_name 
                     FROM product
                     INNER JOIN category 
                     ON product.category_id = category.id_category
                     ORDER BY product.created_at DESC
                     LIMIT 8";
$stmt_new_arrivals = $pdo->prepare($sql_new_arrivals);
$stmt_new_arrivals->execute();
$new_arrivals = $stmt_new_arrivals->fetchAll(PDO::FETCH_ASSOC);

$sql_hot_products = "SELECT product.*, category.name AS category_name 
                     FROM product
                     INNER JOIN category 
                     ON product.category_id = category.id_category
                     LIMIT 8";
$stmt_hot_products = $pdo->prepare($sql_hot_products);
$stmt_hot_products->execute();
$hot_products = $stmt_hot_products->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="bodi">
    <div id="carouselExample" class="carousel slide mb-5">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/banner10.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner6.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner9.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner7.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner8.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">  
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner11.jpg" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">  
            </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<div id="firsttext" class="text-center">
    <h2 class="mt-5 mb-5" style="color:rgb(48,120,156); font-weight: bold;">Sản Phẩm Mới Vừa Về</h2>
</div>


<div class="container">
    <div class="row d-flex flex-wrap justify-content-center">
        <?php foreach ($new_arrivals as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-10 mb-4 d-flex flex-column">
                <div class="product d-flex flex-column h-100 shadow-lg" style="border-radius: 8px; overflow: hidden; transition: transform 0.3s ease;">
                    <div class="position-relative">
                        <img src="<?= $product['image']; ?>" alt="Product Image" style="width: 100%; height: 250px; object-fit: cover;">
                    </div>
                    <div class="details d-flex flex-column p-3" style="background-color: #f9f9f9; flex-grow: 1;">
                        <p class="text-muted mb-1">Loại: <?= $product['category_name']; ?></p>
                        <h5 class="mb-2" style="font-size: 1.2rem; font-weight: 600;"> <?= $product['name']; ?> </h5>
                        <p class="text-muted mb-2">Giá: <?= number_format($product['price'], 0, ',', '.') . ' ₫'; ?></p>
                        <button class="btn btn-outline-primary mt-auto" style="margin-top: auto;" onclick="window.location.href='pages/details.php?id=<?= $product['id_product']; ?>'">Thông tin sản phẩm</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="container my-5">
    <div id="featuredSection" class="text-center">
        <h2 class="mb-5" style="color:rgb(48,120,156); font-weight: bold;">Sản Phẩm Nổi Bật</h2>
        <div class="row">
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h5 class="card-title">Son môi quyến rũ</h5>
                        <p class="card-text">
                        Son môi lâu trôi với sắc màu quyến rũ, phù hợp cho mọi độ tuổi. Hoàn hảo cho mọi dịp, từ công sở đến những buổi tiệc tối!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-lg border-0">
            
                    <div class="card-body">
                        <h5 class="card-title">Nước hoa hương hoa tươi mát</h5>
                        <p class="card-text">
                        Chai nước hoa với hương hoa tươi mát và quyến rũ, là món quà hoàn hảo cho bất kỳ ai. Hãy để hương thơm này làm bừng sáng ngày của bạn!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-lg border-0">
                    
                    <div class="card-body">
                        <h5 class="card-title">Kem dưỡng da mềm mại</h5>
                        <p class="card-text">
                        Sở hữu làn da mềm mại và mịn màng với kem dưỡng da chất lượng cao, giúp làn da bạn luôn được chăm sóc tốt nhất!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="firsttext" class="text-center">
    <h2 class="mt-5 mb-5" style="color:rgb(48,120,156); font-weight: bold;">Khuyến Nghị</h2>
</div>

<div class="container">
    <div class="row d-flex flex-wrap justify-content-center">
        <?php foreach ($hot_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-10 mb-4 d-flex flex-column">
                <div class="product d-flex flex-column h-100 shadow-lg" style="border-radius: 8px; overflow: hidden; transition: transform 0.3s ease;">
                    <div class="position-relative">
                        <img src="<?= $product['image']; ?>" alt="Product Image" style="width: 100%; height: 250px; object-fit: cover;">
                    </div>
                    <div class="details d-flex flex-column p-3" style="background-color: #f9f9f9; flex-grow: 1;">
                        <p class="text-muted mb-1">Loại: <?= $product['category_name']; ?></p>
                        <h5 class="mb-2" style="font-size: 1.2rem; font-weight: 600;"> <?= $product['name']; ?> </h5>
                        <p class="text-muted mb-2">Giá: <?= number_format($product['price'], 0, ',', '.') . ' ₫'; ?></p>
                        <button class="btn btn-outline-primary mt-auto" style="margin-top: auto;" onclick="window.location.href='pages/details.php?id=<?= $product['id_product']; ?>'">Thông tin sản phẩm</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="container mt-5">
    <h2 class="text-center mb-5" style="color:rgb(48,120,156); font-weight: bold;">Khách Hàng Của Chúng Tôi Nói Gì:</h2>
    <div class="row">
        <div class="col-lg-4">
            <blockquote class="blockquote text-center">
                <p>"Những sản phẩm mỹ phẩm tốt nhất mà tôi từng sử dụng! Làn da của tôi trở nên mềm mại và rạng rỡ hơn sau mỗi lần sử dụng!"
               </p>
                <foote class="blockquote-footer">Dương A Ly, <cite title="Source Title">Happy Customer</cite></foote>
            </blockquote>
        </div>
        <div class="col-lg-4">
            <blockquote class="blockquote text-center">
                <p>"Chất lượng tuyệt vời và giao hàng nhanh. Linh Nga luôn làm tôi hài lòng!"</p>
                <foote class="blockquote-footer">Mạnh Tạ, <cite title="Source Title">Satisfied Buyer</cite></foote>
            </blockquote>
        </div>
        <div class="col-lg-4">
            <blockquote class="blockquote text-center">
                <p>"Nước hoa ở đây thật quyến rũ! Giá cả hợp lý và có nhiều lựa chọn."</p>
                <foote class="blockquote-footer">Thuỳ Dương, <cite title="Source Title">Frequent Shopper</cite></foote>
            </blockquote>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php
ob_end_flush();
?>
</body>
<head>
<style>
       .carousel-control-prev-icon,
        .carousel-control-next-icon {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: #87CEEB; 
            transform: scale(1.2);          
        }
    </style>
</head>
</html>
