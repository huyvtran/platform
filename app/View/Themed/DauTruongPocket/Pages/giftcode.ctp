<!DOCTYPE html>
<html>
<head>
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <?php
    $webroot = '/uncommon/aot';
    echo $this->element('meta_data');
    echo $this->Html->css('/uncommon/all-bootrap/css/bootstrap.min.css');
    echo $this->Html->css('/uncommon/navtop-login/css/style.css');
    echo $this->Html->css($webroot . '/css/styles_giftcode.css');
    echo $this->fetch('css');
    ?>
    <?php
    if (!isset($currentGame)) {
        $currentGame = "";
    }
    $gameConfigs        = $this->Cms->getLinkForSite($currentGame);
    ?>

</head>
<body class="rs">
<?php echo $this->element('nav-login'); ?>
<?php echo $this->element('header-c'); ?>
<section class="main-content">
    <div class="container">
        <div class="action-link">
            <a href="#tab1" class="sprite tab1" data-tab="tab1">Hướng dẫn</a>
            <a href="#tab2" class="sprite tab2 active" data-tab="tab2">Nhận code</a>
        </div>
        <div class="tabs-container">
            <div id="tab1" class="content-tab" style="display:none">
                <?php
                $article = $this->Cms->getArticle('guides', 'huong-dan-nhan-giftcode');
                if (!empty($article)) {
                    echo $article['Article']['parsed_body'];
                }
                ?>
            </div>
            <div class="content-tab" id="tab2" >

                <p>Bạn cần phải đăng nhập để nhận đươc Giftcode</p>
                <p>Xin chào tài khoản <b class="color-featured">Funtap</b></p>
                <form action="#" method="post">
                    <input type="text" placeholder="Tên đăng nhập" class="input-control">
                    <input type="password" placeholder="Mật khẩu" class="input-control">
                    <div id="result">ED092D</div>
                    <button class="button orange">Đăng nhập</button>
                    <button class="button blue">Đăng nhập qua Facebook</button>
                </form>
            </div>
        </div>
    </div>
</section>
    <?php echo $this->element('footer') ?>
    <?php echo $this->element('footer_giftcode_script'); ?>
    <?php echo $this->element('login_script'); ?>
</body>
</html>