<?php
if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (!isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

$sql_fetch_orders = "
    SELECT 
        orders.id_order, 
        orders.user_username, 
        orders.order_date, 
        orders.total_price, 
        orders.payment_method,
        orders.status, 
        GROUP_CONCAT(order_detail.product_name SEPARATOR ', ') AS product_names,
        GROUP_CONCAT(order_detail.quantity SEPARATOR ', ') AS product_quantities
    FROM orders
    JOIN order_detail ON orders.id_order = order_detail.order_id
    GROUP BY orders.id_order";

$stmt_orders = $pdo->prepare($sql_fetch_orders);
$stmt_orders->execute();
$orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_order_status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        
        $sql_update_order_status = "UPDATE orders SET status = :status WHERE id_order = :order_id";
        $stmt_update_order_status = $pdo->prepare($sql_update_order_status);
        $stmt_update_order_status->execute(['status' => $status, 'order_id' => $order_id]);
        


        echo "<script type='text/javascript'>window.location.href = 'admin.php?section=orders';</script>";
        exit();
    }
}
?>

<h2 class="mb-4">Manage Orders</h2>
<table class="table table-hover table-bordered mt-3">
    <thead class="table-light">
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Date</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['id_order']); ?></td>
                <td><?= htmlspecialchars($order['user_username']); ?></td>
                <td><?= htmlspecialchars($order['order_date']); ?></td>
                <td><?= number_format($order['total_price'], 0, ',', '.') . ' ₫'; ?></td>
                <td><?= htmlspecialchars($order['payment_method']); ?></td>
                <td>
                    <form method="post" class="d-flex align-items-center">
                        <input type="hidden" name="order_id" value="<?= $order['id_order']; ?>">
                        <select name="status" class="form-select me-2" <?= $order['status'] == 'Done' ? 'disabled' : ''; ?>>
                            <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Shipping" <?= $order['status'] == 'Shipping' ? 'selected' : ''; ?>>Shipping</option>
                            <option value="Done" <?= $order['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                        </select>
                </td>
                <td>
                    <button type="submit" name="update_order_status" class="btn btn-custom" <?= $order['status'] == 'Done' ? 'disabled' : ''; ?>>
                        Update
                    </button>
                </td>
                </form>
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

    .form-select {
        min-width: 150px;
    }
</style>
