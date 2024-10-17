<?php

include '../partials/header.php';
include '../includes/func.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];
    if (isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}


$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


$sql = "SELECT product.*, category.name AS category_name 
        FROM product
        INNER JOIN category 
        ON product.category_id = category.id_category
        WHERE product.id_product = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$product) {
    echo "<h2>Product not found</h2>";
    exit;
}


if (isset($_POST['buy_now']) || isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['id_user'])) {

        header('Location: ' . BASE_URL . 'pages/auth.php');
        exit;
    } else {

        $user_username = $_SESSION['username'];
        $quantity = (int) $_POST['quantity'];


        addToCart($pdo, $user_username, $product_id, $product['price'], $quantity);

        if (isset($_POST['buy_now'])) {

            echo "<script type='text/javascript'>window.location.href = 'cart.php';</script>";
            exit();

        } else if (isset($_POST['add_to_cart'])) {

            echo '<script>
                    alert("Product added to cart successfully!");
                  </script>';
        }
    }
}

?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/details.css">

<div class="container mt-5">
    <div class="row">
        
        <div class="col-md-7">
            <div class="product-image-wrapper shadow rounded overflow-hidden d-flex justify-content-center">
                <img src="../<?= $product['image']; ?>" alt="Product Image" class="product-img img-fluid">
            </div>
        </div>

        
        <div class="col-md-5 ps-">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $product['category_name']; ?></li>
                    </ol>
                </nav>
            </div>
            <h2 class="product-title text-primary" style="color: rgb(48,120,156); font-weight: bold;">
                <?= $product['name']; ?>
            </h2>
            <p class="text-muted" style="font-style: italic;">
                Category: <?= $product['category_name']; ?>
            </p>
            <p class="price fw-bold" style="font-size: 1.5rem; color: rgb(48,120,156);">
                <?= number_format($product['price'], 0, ',', '.') . ' ₫'; ?>
            </p>
            <p class="product-description text-muted">
                <?= $product['shortdesc']; ?>
            </p>

            <div class="d-flex align-items-center mb-3">
                <form method="post" action="" id="productForm">
                    <div class="input-group mb-3">
                        <input type="number" name="quantity" class="form-control text-center" value="1" min="1"
                            style="max-width: 80px;">
                        <button type="submit" name="add_to_cart" class="btn btn-outline-primary btn-signature ms-2">Add
                            to Cart</button>
                        <button type="submit" name="buy_now" class="btn btn-primary ms-2"
                            style="background-color: rgb(48,120,156); border-color: rgb(48,120,156);">Buy Now</button>
                    </div>
                </form>
            </div>

            
            <div id="successAlert" class="alert alert-success mt-3" style="display:none;">
                Product added to cart successfully!
            </div>
        </div>
    </div>

    
    <div class="product-info mt-5 p-4 bg-light rounded shadow">
        <h4 class="text-primary" style="color: rgb(48,120,156); font-weight: bold;">Thông Tin Về Sản Phẩm:</h4>
        <p><?= $product['longdesc']; ?></p>
    </div>
</div>

<?php include '../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>