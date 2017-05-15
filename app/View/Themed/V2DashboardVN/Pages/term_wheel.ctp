<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <title>Vòng Quay May Mắn</title>
    <?php
    echo $this->Html->css('/uncommon/dashboard_v2/css/wheel.css');
    echo $this->fetch('css');
    ?>
    <?php
    echo $this->Html->script('/uncommon/dashboard_v2/js/jquery.min.js');
    echo $this->Html->script('/uncommon/dashboard_v2/js/Winwheel.js');
    echo $this->Html->script('/uncommon/dashboard_v2/js/TweenMax.min.js');
    echo $this->fetch('script');
    ?>

</head>
<body>
<nav  class="tab-controls">
    <div class="container">

        <ul class="list-unstyled clearfix">
            <li  ><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'round_lucky')) ?>">Quay thưởng</a></li>
            <li class="active"><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'term_wheel')) ?>">Thể lệ</a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'history_wheel')) ?>">Lịch sử quay</a></li>
        </ul>

    </div>
</nav>
<div class="container">
    <h3 class="small-title">Thời hạn tham gia </h3>
    <p>Từ 30/07/2016 đến hết ngày 05/08/2016</p>

    <h3 class="small-title">Giải thưởng</h3>
    <ul class="list-default">
        <li>01 Iphone 6S 64GB</li>
        <li>14 sạc dự phòng 10,000 mAh</li>
        <li>Hàng trăm nghìn FunCoin miễn phí</li>
    </ul>

    <h3 class="small-title">Cách thức tham gia</h3>
    <ul class="list-default">
        <li>
            Mỗi ngày, người chơi được quay miễn phí 1 lần. Sau đó có thể mua lượt quay bằng FunCoin với số lượt quay tối đa theo hạng thành viên như sau: <br>
            + Hội viên: 2 lần<br>
            + Silver 1, Silver 2, Silver 3: 3 lần<br>
            + Gold 1, Gold 2: 5 lần<br>
            + Gold 3: 8 lần<br>
            + Platinum: 10 lần<br>
            Người chơi sẽ sử dụng 50 FunCoin cho mỗi lần mua lượt quay
        </li>
        <li>Sau ngày 05/08/2016, các thông báo trúng thưởng xuất hiện tại Vòng Quay May Mắn đều không còn tác dụng nhận thưởng của chương trình.</li>
    </ul>

    <h3 class="small-title">Nhận thưởng</h3>
    <ul class="list-default">
        <li>Quà FunCoin: Tặng trực tiếp vào tài khoản người chơi ngay khi trúng thưởng.</li>
        <li>Quà ngoài game (iPhone 6s, Sạc dự phòng): Liên hệ bộ phận CSKH (1900 636 452) để được hỗ trợ nhận giải.<br>
            <b>Lưu ý:</b> Tất cả phần thưởng ngoài game sẽ được trao tặng sau thời gian kết thúc chương trình.
        </li>
        <li>Để nhận thưởng, người chơi may mắn phải thực hiện các thủ tục sau:
            <ul class="list-default">
                <li>Cung cấp bằng chứng xác định trúng thưởng: tên tài khoản FunID, tên nhân vật mà người chơi đã dùng để quay số.
                    Bằng chứng xác định trúng thưởng chỉ hợp lệ khi trùng khớp với thông tin người chơi trúng thưởng đã được hệ thống ghi nhận trước đó.
                </li>
                <li>Nộp bản sao giấy chứng minh nhân dân (CMND). Thông tin trên bản sao CMND nộp lại để nhận giải thưởng phải trùng khớp với thông tin
                    CMND mà người chơi đã cung cấp khi có thông báo trúng thưởng.
                </li>
                <li>Cung cấp họ tên, địa chỉ, số điện thoại liên lạc.</li>
                <li>Ký biên bản giao nhận giải thưởng.</li>
                <li>Đóng các khoản phí (nếu có) liên quan đến việc nhận giải thưởng.</li>
            </ul>
        </li>
    </ul>
</div>
</body>
</html>