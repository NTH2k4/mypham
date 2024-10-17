<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents('php://input');
    $result = json_decode($data, true);

    $orderId = $result['orderId'];
    $resultCode = $result['resultCode'];

    // Xử lý logic tại đây, ví dụ: cập nhật trạng thái đơn hàng

    echo "OK";
}
?>