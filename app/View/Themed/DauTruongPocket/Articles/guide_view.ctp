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
    <div class="guide-detail w685">
        <header class="header-detail">
            <h1><span><?php echo $article['Article']['title'];?></span></h1>
            <a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'guides')); ?>" class="back sprite">Quay lại</a>
        </header>
        <div class="entry-detail">
            <?php
                echo $article['Article']['parsed_body'];
            ?>
            <div class="fb-like" data-href="<?php  echo Router::url( $this->here, true ); ?>" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
            <div class="dataTag" itemprop="articleTag">
                <span>Tags: </span>
                <?php if(count($listTags)){foreach($listTags as $key => $tag){ ?>
                    <a href="<?php echo $this->Html->url('/tag/'.$key); ?>"><?php echo $tag ;?> </a>,
                <?php }} ?>
            </div>
            <div class="box-cmfb"><div class="fb-comments" data-href="<?php  echo Router::url( $this->here, true ); ?>" data-numposts="5" data-width="660"></div></div>
        </div>

    </div>
</section>
<?php echo $this->element('footer') ?>
<?php echo $this->element('footer_script'); ?>
</body>
</html>