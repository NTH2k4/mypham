<?php

include '../partials/header.php';

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];


    if (isAdmin($pdo, $user_id)) {
    
        header('Location: ' . BASE_URL . 'pages/admin.php');
        exit;
    }
}


?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/order.css">

<div class="ct">
<div class="container c
    <h2 class="text-center mb-4">Your Orders</h2>
    
    
    <div class="order mb-4">
        <div class="order-id">Order #12345</div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product A</td>
                    <td>2</td>
                    <td>$40</td>
                </tr>
                <tr>
                    <td>Product B</td>
                    <td>1</td>
                    <td>$20</td>
                </tr>
                <tr>
                    <td>Product C</td>
                    <td>3</td>
                    <td>$15</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td>$95</td>
                </tr>
            </tbody>
        </table>
    </div>

    
    <div class="order mb-4">
        <div class="order-id">Order #12346</div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product D</td>
                    <td>1</td>
                    <td>$60</td>
                </tr>
                <tr>
                    <td>Product E</td>
                    <td>5</td>
                    <td>$10</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td>$110</td>
                </tr>
            </tbody>
        </table>
    </div>

    

    
    <a href="#" class="btn btn-custom w-100 mt-4">Back to Account</a>
</div>
</div>
<?php

include '../partials/footer.php';


?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>