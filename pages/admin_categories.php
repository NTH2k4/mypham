<?php
if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (!isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $category_name = $_POST['category_name'];
        $sql_add_category = "INSERT INTO category (name) VALUES (:name)";
        $stmt_add_category = $pdo->prepare($sql_add_category);
        $stmt_add_category->execute(['name' => $category_name]);
    } elseif (isset($_POST['edit_category'])) {
        $category_id = $_POST['category_id'];
        $category_name = $_POST['category_name'];
        $sql_edit_category = "UPDATE category SET name = :name WHERE id_category = :id";
        $stmt_edit_category = $pdo->prepare($sql_edit_category);
        $stmt_edit_category->execute(['name' => $category_name, 'id' => $category_id]);
    } elseif (isset($_POST['delete_category'])) {
        $category_id = $_POST['category_id'];
        $sql_delete_category = "DELETE FROM category WHERE id_category = :id";
        $stmt_delete_category = $pdo->prepare($sql_delete_category);
        $stmt_delete_category->execute(['id' => $category_id]);
    }
}

$sql_fetch_categories = "SELECT * FROM category";
$stmt_categories = $pdo->prepare($sql_fetch_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Manage Categories</h2>


<form method="post" action="" class="mb-4">
    <div class="input-group mb-3" style="max-width: 400px;">
        <input type="text" name="category_name" class="form-control" placeholder="Add new category" required>
        <button type="submit" name="add_category" class="btn btn-primary" style="border-radius: 0 20px 20px 0;">Add Category</button>
    </div>
</form>


<table class="table table-hover table-bordered mt-3">
    <thead class="table-light">
        <tr>
            <th>Category ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <?php
            // Check if the category is used in any products
            $category_id = $category['id_category'];
            $sql_check_products = "SELECT COUNT(*) AS total_products FROM product WHERE category_id = :category_id";
            $stmt_check_products = $pdo->prepare($sql_check_products);
            $stmt_check_products->execute(['category_id' => $category_id]);
            $total_products = $stmt_check_products->fetch(PDO::FETCH_ASSOC)['total_products'];
            ?>

            <tr>
                <td><?= $category['id_category']; ?></td>
                <td>
                    <form method="post" style="display:flex; align-items: center;">
                        <input type="hidden" name="category_id" value="<?= $category['id_category']; ?>">
                        <input type="text" name="category_name" class="form-control me-2" value="<?= $category['name']; ?>" required>
                </td>
                <td>
                        <button type="submit" name="edit_category" class="btn btn-custom me-2">Edit</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="category_id" value="<?= $category['id_category']; ?>">
                        <button type="submit" name="delete_category" class="btn btn-danger"
                            <?= ($total_products > 0) ? 'disabled' : ''; ?>>Delete</button>
                    </form>
                    <?php if ($total_products > 0): ?>
                        <small class="text-muted">Can't delete: <?= $total_products; ?> product(s) in this category</small>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


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

    .table td, .table th {
        vertical-align: middle;
    }

    .input-group .form-control {
        border-radius: 20px 0 0 20px;
    }

    .input-group .btn-primary {
        border-radius: 0 20px 20px 0;
    }

    .form-control {
        min-width: 100%;
    }
</style>
