<?php
if (!isset($currentGame)) {
    $currentGame = "";
}
$gameConfigs        = $this->Cms->getLinkForSite($currentGame);
$appstore_link      = (isset($gameConfigs['appstore_link'])&& $gameConfigs['appstore_link'] != '') ? $gameConfigs['appstore_link'] : "";
$google_play_link   = (isset($gameConfigs['google_play_link'])&& $gameConfigs['google_play_link'] != '') ? $gameConfigs['google_play_link'] : "";
$apk_link           = (isset($gameConfigs['apk_link'])&& $gameConfigs['apk_link'] != '') ? $gameConfigs['apk_link'] : "";
?>
<header id="header">
    <div class="top-header clearfix">
        <div class="age-rec"><img src="http://cdn.smobgame.com/newfolder/limit/12t.png" width="65" alt=""></div>
        <div class="container">
            <?php if($this->request->controller == 'articles' && $this->request->action == 'view' ){ ?>
                <h1 class="rs">
                    <div class="logo"><a class="" href="<?php echo $this->Html->url('/home') ?>"><img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/logo.png" class="img-fix" alt=""><?php echo isset($currentGame['title'])?$currentGame['title']:''; ?></a></div>
                    <a href="<?php echo $this->Html->url('/home') ?>"  class="logopc"><?php echo isset($currentGame['title'])?$currentGame['title']:''; ?></a>
                </h1>
            <?php }else{ ?>
                <h1 class="rs">
                    <div class="logo"><a class="" href="<?php echo $this->Html->url('/home') ?>"><img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/logo.png" class="img-fix" alt=""><?php echo isset($currentGame['title'])?$currentGame['title']:''; ?></a></div>
                    <a href="<?php echo $this->Html->url('/home') ?>"  class="logopc"><?php echo isset($currentGame['title'])?$currentGame['title']:''; ?></a>
                </h1>

            <?php } ?>
            <a href="javascript:void(0);" class="toggle-main-nav sprite">Navigation Toggle</a>
            <ul class="list-unstyled main-nav cf">
                <li>
                    <a href="<?php echo $this->Html->url('/home') ?>">trang chủ</a>
                </li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'news+events')); ?>">tin mới</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'features')); ?>">Đặc Sắc</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'guides')); ?>">hướng dẫn</a></li>
                <li><a href="https://www.facebook.com/dautruongmegaxy/" target="_blank">Fanpage</a></li>
                <li><a href="http://funtap.vn/khach-hang-than-thiet" target="_blank">KHTT FUNTAP</a></li>
            </ul>
            <ul class="list-unstyled mobile-main-nav" style="display:none">
                <li>
                    <a href="<?php echo $this->Html->url('/home') ?>">trang chủ</a>
                </li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'news+events')); ?>">tin mới</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'features')); ?>">Đặc Sắc</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'guides')); ?>">hướng dẫn</a></li>
                <li><a href="https://www.facebook.com/dautruongmegaxy/" target="_blank">Fanpage</a></li>
                <li><a href="http://funtap.vn/khach-hang-than-thiet" target="_blank">KHTT FUNTAP</a></li>
            </ul>
        </div>
    </div>
    <div class="container">

        <div class="bottom-header clearfix">
            <ul class="list-unstyled">
                <li class="lv1">
                    <a href="<?php echo $appstore_link; ?>" class="sprite"></a>
                </li>
                <li class="lv2">
                    <a href="<?php echo $google_play_link; ?>" class="sprite"></a>
                </li>
                <li class="lv3">
                    <a href="<?php echo $apk_link; ?>" class="sprite"></a>
                </li>
                <li class="lv4">
                    <a href="<?php echo $apk_link; ?>" class="sprite"></a>
                </li>
                <li class="lv5"><a href="javascript:void(0)" class="sprite" data-toggle="modal" data-target="#trailer">&nbsp;</a></li>
                <li class="lv6">
                    <a href="javascript:void(0)" class="sprite"></a>
                </li>
            </ul>
        </div>
    </div>
</header>