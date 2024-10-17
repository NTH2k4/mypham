<?php
include '../partials/header.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: ' . BASE_URL . 'pages/auth.php');
    exit;
}

$user_username = $_SESSION['username'];

$sql_user = "SELECT address, phone, fullname FROM user WHERE username = :username";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([':username' => $user_username]);
$user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Đặt giá trị mặc định nếu không lấy được từ cơ sở dữ liệu
$user_address = $user_data['address'] ?? '';
$user_phone = $user_data['phone'] ?? '';
$user_fullname = $user_data['fullname'] ?? '';

if (isset($_POST['update_address'])) {
    $new_address = $_POST['address'];
    $new_phone = $_POST['phone'];
    $new_fullname = $_POST['fullname'];
    $sql_update_address = "UPDATE user SET address = :address, phone = :phone, fullname = :fullname WHERE username = :username";
    $stmt_update_address = $pdo->prepare($sql_update_address);
    $stmt_update_address->execute([':address' => $new_address, ':phone' => $new_phone, ':fullname' => $new_fullname, ':username' => $user_username]);

    // Cập nhật lại các giá trị sau khi lưu để hiển thị đúng trong form
    $user_address = $new_address;
    $user_phone = $new_phone;
    $user_fullname = $new_fullname;
}



if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $sql_delete = "DELETE FROM cart WHERE user_username = :user_username AND product_id = :product_id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':user_username' => $user_username, ':product_id' => $product_id]);
    echo "<script type='text/javascript'>window.location.href = 'cart.php';</script>";
    exit();
}

$sql_cart = "SELECT cart.*, product.name AS product_name, product.image, category.name AS category_name, product.price AS current_price
             FROM cart
             INNER JOIN product ON cart.product_id = product.id_product
             INNER JOIN category ON product.category_id = category.id_category
             WHERE cart.user_username = :user_username";
$stmt_cart = $pdo->prepare($sql_cart);
$stmt_cart->execute([':user_username' => $user_username]);
$cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

$total_quantity = 0;
$total_price = 0;
$total_products = count($cart_items);

foreach ($cart_items as $item) {
    $total_quantity += $item['quantity'];
    $total_price += $item['price_at_cart'] * $item['quantity'];
}

if (isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'];

    $sql_order = "INSERT INTO orders (user_username, total_price, status, payment_method) 
                  VALUES (:user_username, :total_price, 'Pending', :payment_method)";
    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([':user_username' => $user_username, ':total_price' => $total_price, ':payment_method' => $payment_method]);

    $order_id = $pdo->lastInsertId();

    foreach ($cart_items as $item) {
        $sql_order_detail = "INSERT INTO order_detail (order_id, product_id, product_name, price_at_purchase, quantity)
                             VALUES (:order_id, :product_id, :product_name, :price_at_purchase, :quantity)";
        $stmt_order_detail = $pdo->prepare($sql_order_detail);
        $stmt_order_detail->execute([
            ':order_id' => $order_id,
            ':product_id' => $item['product_id'],
            ':product_name' => $item['product_name'],
            ':price_at_purchase' => $item['price_at_cart'],
            ':quantity' => $item['quantity']
        ]);
    }

    $sql_clear_cart = "DELETE FROM cart WHERE user_username = :user_username";
    $stmt_clear_cart = $pdo->prepare($sql_clear_cart);
    $stmt_clear_cart->execute([':user_username' => $user_username]);

    echo "<script>
        alert('Bạn đã đặt hàng thành công!');
        window.location.href = '" . BASE_URL . "index.php';
    </script>";
    exit;
}
?>


<!-- ============================MOMO========================= -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $paymentMethod = $_POST['payment_method'];

    if ($paymentMethod == 'MoMo') {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMO';
        $accessKey = 'F8BBA842ECF85';
        $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
        $orderId = time() . "";
        $orderInfo = "Thanh toán qua MoMo";
        $amount = "10000"; // Số tiền test
        $redirectUrl = "https://your-website.com/return.php";
        $ipnUrl = "https://your-website.com/notify.php";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Điều hướng tới trang thanh toán của MoMo
        header('Location: ' . $jsonResult['payUrl']);
        exit();
    }
}

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
    );
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
<!-- =============================================================== -->

<section class="h-100 h-custom" style="background-color: #d2c9ff;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0 p-4">
                            <?php if ($total_products == 0): ?>
                                <div class="text-center">
                                    <h1 class="fw-bold mb-0">Your Cart is Empty</h1>
                                    <a href="<?= BASE_URL; ?>pages/shop.php" class="btn btn-secondary mt-4">Go to Shop</a>
                                </div>
                            <?php else: ?>
                                <div class="col-lg-8">
                                    <div class="p-5">





                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <h1 class="fw-bold mb-0">Giỏ Hàng</h1>
                                            <h6 class="mb-0 text-muted"><?= $total_products ?> items</h6>
                                        </div>
                                        <hr class="my-4">

                                        <?php foreach ($cart_items as $item): ?>
                                            <div class="row mb-4 d-flex justify-content-between align-items-center">
                                                <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <img src="../<?= $item['image']; ?>" class="img-fluid rounded-3"
                                                        alt="<?= $item['product_name']; ?>">
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-3">
                                                    <h6 class="text-muted"><?= $item['category_name']; ?></h6>
                                                    <h6 class="mb-0"><?= $item['product_name']; ?></h6>
                                                    <p class="mb-0">Price:
                                                        <?= number_format($item['price_at_cart'], 0, ',', '.') . ' ₫'; ?></p>
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                    <input id="form1" name="quantity" value="<?= $item['quantity']; ?>"
                                                        type="number" class="form-control form-control-sm" disabled />
                                                </div>
                                                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                    <h6 class="mb-0">
                                                        <?= number_format($item['price_at_cart'] * $item['quantity'], 0, ',', '.') . ' ₫'; ?>
                                                    </h6>
                                                </div>
                                                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                    <form method="post" action="">
                                                        <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                                                        <button type="submit" name="delete_product" class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                            <hr class="my-4">
                                        <?php endforeach; ?>

                                        <div class="pt-5 back-to-shop">
                                            <h6 class="mb-0"><a href="<?= BASE_URL; ?>pages/shop.php" class="text-body back-to-shop-link"><i
                                                    class="fas fa-long-arrow-alt-left me-2"></i>Quay lại cửa hàng</a></h6>
                                        </div>
                                            
                                    <?php endif; ?>

                                    </div>
                                </div>
                                <div class="col-lg-4 bg-body-tertiary">
                                    <?php if ($total_products > 0): ?>
                                        <div class="p-5">
                                            <h3 class="fw-bold mb-5 mt-2 pt-1">Cộng Giỏ Hàng</h3>
                                            <hr class="my-4">

                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 class="text-uppercase">Items <?= $total_products ?></h5>
                                                <h5><?= number_format($total_price, 0, ',', '.') . ' ₫'; ?></h5>
                                            </div>

                                            <h5 class="text-uppercase mb-3">Vận chuyển</h5>

                                            <div class="mb-4 pb-2">
                                                <select name="shipping_method" class="form-select">
                                                    <option value="Express" selected>Express Delivery - Free</option>
                                                </select>
                                            </div>
                                            <!-- DIA CHI -->
                                            <h5 class="text-uppercase mb-3">Địa chỉ nhận hàng</h5>
                                            <form method="post" action="" id="address-form">
                                                <input type="text" class="form-control mb-3" name="fullname" placeholder="Nhập họ tên" value="<?= htmlspecialchars($user_fullname); ?>" required>
                                                <input type="text" class="form-control mb-3" name="phone" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($user_phone); ?>" required>
                                                <input type="text" class="form-control mb-3" name="address" placeholder="Nhập địa chỉ nhận hàng" value="<?= htmlspecialchars($user_address); ?>" required>
                                                <button type="submit" name="update_address" class="btn btn-primary btn-block">Cập nhật địa chỉ</button>
                                            </form>
                                        <!-- DIA CHI -->
                                            <hr class="my-4">
<!-- ========================================================= -->
<form method="post" action="" id="checkout-form">
    <h5 class="text-uppercase mb-3">Phương Thức Thanh Toán</h5>
    <div>
        <label><input type="radio" name="payment_method" value="COD" checked> Thanh toán khi nhận hàng (COD)</label>
        <br>
        <label><input type="radio" name="payment_method" value="Online"> Visa/Mastercard (Online)</label>
        <br>
        <label><input type="radio" name="payment_method" value="MoMo"> Ví MoMo</label>
    </div>

    <div class="payment-container" id="online-payment-section" style="display: none;">
        <h3 class="payment-title">
            <img src="../assets/images/iconatm.png" alt="ATM Icon" class="atm-icon"> Nhập thông tin thẻ để thanh toán
        </h3>

        <div class="card-image-container">
            <img src="../assets/images/thenganhang.png" alt="Thẻ Ngân Hàng" class="bank-card-img">
        </div>

        <div class="payment-form">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Số thẻ" id="card-number">
                <input type="text" class="form-control" placeholder="Ngày phát hành (MM/YY)" id="expiry-date">
            </div>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tên chủ thẻ" id="card-name">
                <input type="text" class="form-control" placeholder="Số điện thoại" id="phone-number">
            </div>
        </div>
    </div>

    <hr class="my-4">
    <button type="submit" name="checkout" class="btn btn-dark btn-block btn-lg">Thanh Toán</button>
</form>

<script>
                                                const paymentMethods = document.getElementsByName('payment_method');
                                                const onlinePaymentSection = document.getElementById('online-payment-section');
                                                const checkoutForm = document.getElementById('checkout-form');

                                                paymentMethods.forEach(method => {
                                                    method.addEventListener('change', function() {
                                                        if (this.value === 'Online') {
                                                            onlinePaymentSection.style.display = 'block';
                                                        } else {
                                                            onlinePaymentSection.style.display = 'none';
                                                        }
                                                    });
                                                });

                                                checkoutForm.addEventListener('submit', function(event) {
                                                    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

                                                    if (selectedPaymentMethod === 'Online') {
                                                        const cardNumber = document.getElementById('card-number').value;
                                                        const expiryDate = document.getElementById('expiry-date').value;
                                                        const cardName = document.getElementById('card-name').value;
                                                        const phoneNumber = document.getElementById('phone-number').value;

                                                        if (!cardNumber || !expiryDate || !cardName || !phoneNumber) {
                                                            event.preventDefault();
                                                            alert('Vui lòng nhập đầy đủ thông tin thanh toán thẻ trước khi thanh toán!');
                                                        }
                                                    }

                                                    const address = document.querySelector('input[name="address"]').value;
                                                    if (!address) {
                                                        event.preventDefault();
                                                        alert('Vui lòng nhập địa chỉ nhận hàng trước khi thanh toán!');
                                                    }
                                                });
                                            </script>





                                        </div>
                                    <?php endif; ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
     .back-to-shop {
        text-align: center;
        align-items: center;
        border-radius: 15px;
        overflow: hidden;
        background-color: #f8f9fa; /* Màu nền nhạt đẹp */
        padding: 40px;
    }

    .back-to-shop-link {
        display: inline-block;
        color: #007bff; /* Màu văn bản đẹp */
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-to-shop-link:hover {
        color: #0056b3; /* Màu văn bản khi hover */
        text-decoration: underline;
        background-color: #e2e6ea; /* Màu nền khi hover */
        padding: 5px 10px;
        border-radius: 10px;
    }
    
    .payment-container {
        max-width: 500px;
        margin: 0 auto;
        text-align: center;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .payment-title {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: #333;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .atm-icon {
        width: 30px;
        height: auto;
        margin-right: 10px;
    }

    .card-image-container {
        margin: 20px 0;
    }

    .bank-card-img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .payment-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .input-group {
        display: flex;
        gap: 15px;
    }

    .input-group .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .input-group .form-control:focus {
        border-color: #ff4081;
        outline: none;
    }
</style>