<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $orderId = $_GET['orderId'];
    $resultCode = $_GET['resultCode'];

    if ($resultCode == '0') {
        echo "Thanh toán thành công! Mã đơn hàng: $orderId";
    } else {
        echo "Thanh toán thất bại! Vui lòng thử lại.";
    }
}
?>