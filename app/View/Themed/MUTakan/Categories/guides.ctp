<!DOCTYPE html>
<html>
<head>
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <?php
    $webroot = '/uncommon/dautruongpk';
    echo $this->element('meta_data');
    echo $this->Html->css('/uncommon/all-bootrap/css/bootstrap.min.css');
    echo $this->Html->css('/uncommon/navtop-login/css/style.css');
    echo $this->Html->css($webroot . '/css/styles_guide.css');
    echo $this->fetch('css');
    ?>
</head>
<body>
<?php echo $this->element('nav-login'); ?>
<?php echo $this->element('header-c'); ?>
<section class="main">
    <div class="step">
        <h2 class="block-title">
            Các bước tham gia
        </h2>
        <ul class="list-unstyled">
            <li class="sprite"><a href="http://hotro.funtap.vn/huong-dan-cai-dat" class="sprite">Cài đặt game</a></li>
            <li class="sprite"><a href="http://hotro.funtap.vn/" class="sprite">Hỗ trợ</a></li>
            <li class="sprite"><a href="http://hotro.funtap.vn/bao-loi" class="sprite">Báo Lỗi</a></li>
        </ul>
    </div>
    <div class="list-guide w685">
        <h2 class="block-title">Cẩm nang hướng dẫn</h2>
        <ul class="list-unstyled ">
            <?php if(count($articles) > 0){
                foreach($articles as $article ){
            ?>
                <li><a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $article['Category']['slug'],"slug"=>$article['Article']['slug'])); ?>" class="sprite"><?php echo $article['Article']['title'];?></a></li>
            <?php }} ?>
        </ul>
    </div>
</section>
<?php echo $this->element('footer') ?>
<?php echo $this->element('footer_script'); ?>
</body>
</html>