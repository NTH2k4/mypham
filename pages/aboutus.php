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

<div class="container mt-5">
    
    <div class="row mb-5 align-items-center">
        <div class="col-md-6 order-md-2 text-center">
            <img src="../assets/uploads/motashop_mypham.jpg" class="img-fluid rounded" alt="Plushie Shop">
        </div>
        <div class="col-md-6 order-md-1 text-center text-md-start">
            <h1 class="display-4 section-title">Welcome to Linh Nga Shop</h1>
            <p class="lead about-text">
            Khám phá thế giới quyến rũ của mỹ phẩm và nước hoa, nơi vẻ đẹp và sự tự tin hòa quyện. Cửa hàng của chúng tôi mang đến cho bạn những sản phẩm chăm sóc sắc đẹp tinh tế, giúp bạn tỏa sáng mỗi ngày.
            </p>
        </div>
    </div>

    
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <h2 class="section-title">Câu chuyện của chúng tôi</h2>
        </div>
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="about-text">
                    Tại <strong>Linh Nga</strong>, chúng tôi tin vào sức mạnh biến đổi của mỹ phẩm và nước hoa trong việc làm nổi bật vẻ đẹp tự nhiên của bạn. Hành trình của chúng tôi bắt đầu từ niềm đam mê với những sản phẩm chất lượng cao và mong muốn chia sẻ sự tự tin và quyến rũ với tất cả mọi người.
                    </p>
                    <p class="about-text">
                    Những gì bắt đầu như một bộ sưu tập nhỏ các sản phẩm mỹ phẩm đã phát triển thành một cộng đồng nơi mọi người có thể tìm thấy các sản phẩm làm đẹp yêu thích của mình. Dù bạn đang tìm kiếm sản phẩm chăm sóc da, trang điểm hay nước hoa, mục tiêu của chúng tôi là mang đến cho bạn những sản phẩm không chỉ làm đẹp mà còn chăm sóc sức khỏe làn da của bạn, được thiết kế với tình yêu và sự chú ý đến từng chi tiết nhỏ.
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-5 align-items-center">
        <div class="col-md-6 text-center">
            <img src="../assets/uploads/motashop_mypham3.jpg" class="img-fluid rounded" alt="Plushie Collection">
        </div>
        <div class="col-md-6">
            <h2 class="section-title text-center text-md-start">Những gì chúng tôi cung cấp</h2>
            <ul class="about-text">
                <li>Bộ sưu tập mỹ phẩm cổ điển và các sản phẩm chăm sóc da vượt thời gian</li>
                <li>Nước hoa với hương thơm quyến rũ, từ nhẹ nhàng đến nồng nàn</li>
                <li>Sản phẩm làm đẹp tùy chỉnh, hoàn hảo để làm quà tặng cá nhân</li>
                <li>Bộ sưu tập mỹ phẩm theo mùa và phiên bản giới hạn</li>
            </ul>
            <p class="about-text">
            Mỗi sản phẩm tại Linh Nga đều được làm bằng tình yêu, đảm bảo bạn nhận được sự chăm sóc và chất lượng tốt nhất. Chúng tôi tự hào cung cấp những sản phẩm làm đẹp không chỉ đẹp mắt mà còn được chế tác từ các thành phần an toàn, chất lượng cao.
            </p>
        </div>
    </div>

    
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <h2 class="section-title">Giá trị của chúng tôi:</h2>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="signature-color-text" style="color: rgb(48,120,156);">Tinh tế</h4>
                    <p class="about-text">
                    Chúng tôi tin vào sức mạnh của vẻ đẹp. Mỹ phẩm và nước hoa của chúng tôi được thiết kế để tôn vinh vẻ đẹp tự nhiên của bạn, mang lại sự tự tin và rạng rỡ mỗi ngày.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="signature-color-text" style="color: rgb(48,120,156);">Chất lượng</h4>
                    <p class="about-text">
                    Chất lượng là trọng tâm trong mọi việc chúng tôi làm. Chúng tôi đảm bảo rằng mọi sản phẩm đều được làm từ các thành phần cao cấp, an toàn và hiệu quả, mang lại kết quả tốt nhất cho làn da và phong cách của bạn.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h4 class="signature-color-text" style="color: rgb(48,120,156);">Cộng đồng</h4>
                    <p class="about-text">
                    Linh Nga không chỉ là một cửa hàng. Chúng tôi là một cộng đồng những người yêu thích làm đẹp, đoàn kết bởi niềm đam mê với mỹ phẩm và nước hoa. Chúng tôi mời bạn trở thành một phần của gia đình đang phát triển này.
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-5 text-center">
        <div class="col-md-12">
            <h2 class="section-title">Tham gia cùng chúng tôi</h2>
            <p class="about-text">
            Dù bạn đang tìm kiếm sản phẩm làm đẹp hoàn hảo cho bản thân hay một món quà đặc biệt, chúng tôi mời bạn khám phá bộ sưu tập của chúng tôi. Hãy cùng chúng tôi tôn vinh sự quyến rũ của mỹ phẩm và nước hoa, và mang về nhà những sản phẩm sẽ làm bạn tỏa sáng mỗi ngày.
            </p>
            <a href="<?php echo BASE_URL; ?>pages/shop.php" class="btn btn-primary mt-3" style="background-color: rgb(48,120,156); border-color: rgb(48,120,156);">Mua Ngay</a>
        </div>
    </div>
</div>

<?php

include '../partials/footer.php';

?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>