<?php
if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (!isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $product_name = $_POST['name'];
        $shortdesc = $_POST['shortdesc'];
        $longdesc = $_POST['longdesc'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $image_path = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $file_type = $_FILES['image']['type'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $upload_dir = '../assets/uploads/';
            $image_path = $upload_dir . basename($file_name);

            if (in_array($file_type, $allowed_types)) {
                move_uploaded_file($file_tmp, $image_path);
            } else {
                echo "Only JPG, JPEG, and PNG formats are allowed.";
                exit;
            }
        }
        $temp_upload_dir = 'assets/uploads/';
        $confirm_image_path = $temp_upload_dir . basename($file_name);

        $sql_add_product = "INSERT INTO product (name, shortdesc, longdesc, price, category_id, image) 
                            VALUES (:name, :shortdesc, :longdesc, :price, :category_id, :image)";
        $stmt_add_product = $pdo->prepare($sql_add_product);
        $stmt_add_product->execute([
            'name' => $product_name,
            'shortdesc' => $shortdesc,
            'longdesc' => $longdesc,
            'price' => $price,
            'category_id' => $category_id,
            'image' => $confirm_image_path
        ]);
    }

    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        $sql_delete_product = "DELETE FROM product WHERE id_product = :id_product";
        $stmt_delete_product = $pdo->prepare($sql_delete_product);
        $stmt_delete_product->execute(['id_product' => $product_id]);
    }
}

$sql_fetch_products = "SELECT product.*, category.name AS category_name FROM product 
                       LEFT JOIN category ON product.category_id = category.id_category";
$stmt_products = $pdo->prepare($sql_fetch_products);
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

$sql_fetch_categories = "SELECT * FROM category";
$stmt_categories = $pdo->prepare($sql_fetch_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Manage Products</h2>


<form method="post" action="" enctype="multipart/form-data" class="mb-4 bg-light p-4 shadow-sm rounded">
    <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Product Name" required>
    </div>
    <div class="mb-3">
        <input type="text" name="shortdesc" class="form-control" placeholder="Short Description" required>
    </div>
    <div class="mb-3">
        <textarea name="longdesc" class="form-control" placeholder="Long Description" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <input type="number" name="price" class="form-control" placeholder="Price" required>
    </div>
    <div class="mb-3">
        <select name="category_id" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id_category']; ?>"><?= htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Product Image (optional)</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/jpeg, image/png, image/jpg">
    </div>
    <button type="submit" name="add_product" class="btn btn-custom">Add Product</button>
</form>


<?php if (count($products) > 0): ?>
    <table class="table table-hover table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="<?php echo BASE_URL; ?><?= $product['image']; ?>" alt="<?= htmlspecialchars($product['name']); ?>"
                                style="width: 50px; height: auto;">
                        <?php else: ?>
                            <img src="path/to/default-image.jpg" alt="No Image" style="width: 50px; height: auto;">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['name']); ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?? 'No Category'; ?></td>
                    <td><?= number_format($product['price'], 0, ',', '.') . ' ₫'; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id_product']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id_product']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No products available.</p>
<?php endif; ?>


<style>
    .btn-custom {
        background-color: rgb(48,120,156);
        border-color: rgb(48,120,156);
        color: white;
    }

    .btn-custom:hover {
        background-color: rgba(48,120,156, 0.9);
        border-color: rgba(48,120,156, 0.9);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(48,120,156, 0.05);
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .form-control,
    .form-select {
        min-width: 100%;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
