<?php
if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];

    if (!isAdmin($pdo, $user_id)) {
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $is_admin = $_POST['isAdmin'];
        $is_disabled = $_POST['isDisabled'];
        
        $sql_update_user = "UPDATE user SET isAdmin = :isAdmin, isDisabled = :isDisabled WHERE id_user = :id_user";
        $stmt_update_user = $pdo->prepare($sql_update_user);
        $stmt_update_user->execute(['isAdmin' => $is_admin, 'isDisabled' => $is_disabled, 'id_user' => $user_id]);
    }
}

$sql_fetch_users = "SELECT * FROM user";
$stmt_users = $pdo->prepare($sql_fetch_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">Manage Users</h2>
    <table class="table table-hover table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Full Name</th>
                <th>Address</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['fullname']); ?></td>
                    <td><?= htmlspecialchars($user['address']); ?></td>
                    <td>
                        <span class="badge <?= $user['isAdmin'] == 1 ? 'bg-primary' : 'bg-secondary'; ?>">
                            <?= $user['isAdmin'] == 1 ? 'Admin' : 'User'; ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $user['isDisabled'] == 1 ? 'bg-danger' : 'bg-success'; ?>">
                            <?= $user['isDisabled'] == 1 ? 'Disabled' : 'Enabled'; ?>
                        </span>
                    </td>
                    <td>
                        <form method="post" class="d-flex align-items-center">
                            <input type="hidden" name="user_id" value="<?= $user['id_user']; ?>">

                            
                            <select name="isAdmin" class="form-select me-2">
                                <option value="0" <?= $user['isAdmin'] == 0 ? 'selected' : ''; ?>>User</option>
                                <option value="1" <?= $user['isAdmin'] == 1 ? 'selected' : ''; ?>>Admin</option>
                            </select>

                            
                            <select name="isDisabled" class="form-select me-2">
                                <option value="0" <?= $user['isDisabled'] == 0 ? 'selected' : ''; ?>>Enabled</option>
                                <option value="1" <?= $user['isDisabled'] == 1 ? 'selected' : ''; ?>>Disabled</option>
                            </select>

                            
                            <button type="submit" name="edit_user" class="btn btn-primary">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<style>
    .btn-primary {
        background-color: rgb(48,120,156);
        border-color: rgb(48,120,156);
    }

    .btn-primary:hover {
        background-color: rgba(48,120,156, 0.9);
        border-color: rgba(48,120,156, 0.9);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(48,120,156, 0.05);
    }

    .badge {
        padding: 0.5em 1em;
        font-size: 0.9em;
    }

    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }

    .form-select {
        min-width: 140px;
    }
</style>
