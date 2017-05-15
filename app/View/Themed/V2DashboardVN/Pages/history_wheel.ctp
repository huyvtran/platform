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
    echo $this->fetch('script');
    ?>
</head>
<body>
<nav  class="tab-controls">
    <div class="container">

        <ul class="list-unstyled clearfix">
            <li  ><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'round_lucky')) ?>">Quay thưởng</a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'term_wheel')) ?>">Thể lệ</a></li>
            <li  class="active" ><a href="javascript:;">Lịch sử quay</a></li>
        </ul>

    </div>
</nav>
<div class="history">
    <div class="container">
        <table>
            <thead>
            <tr>
                <th>Ngày</th>
                <th>Phần thưởng</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($user_prize_give) > 0){
                foreach($user_prize_give as $prize){
            ?>
            <tr>
                <td><?php echo date('d/m/Y',$prize['PrizeUser']['time']);  ?></td>
                <td><?php echo $prize['PrizeUser']['prize_name'];  ?></td>
            </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>