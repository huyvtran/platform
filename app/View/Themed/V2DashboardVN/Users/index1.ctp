<?php

$MobileDetect = new Mobile_Detect();
?>
<section id="wrapper">
    <article class="flogin">
        <h3 class="mainTitle rs"> <?php echo __('Đăng nhập để chơi') ?>!</h3>
        <?php echo $this->Session->flash("error_fb"); ?>
        <?php echo $this->Session->flash("error"); ?>
        <?php
        if (empty($currentGame) || $this->Nav->showFunction('hide_login_facebook', $currentGame)) {
            ?>
            <a class="fbutton btn-facebook" href="javascript:MobAppSDKexecute('mobLoginStartFb', {})">
                Facebook
                <?php
                if($currentGame['language_default'] == 'vie'){
                    if (empty($currentGame) || $this->Nav->showFunction('hide_popup_coin', $currentGame)) {
                        ?>
                        <span class="lspr fs-coin">10</span>
                    <?php }} ?>
            </a>
            <p class="ftext rs"><?php echo __('Đăng nhập bằng Facebook giúp bảo vệ tài khoản và chơi game trên nhiều thiết bị.') ?></p>
        <?php } ?>
        <!--            --><?php //if (!$MobileDetect->isAndroidOS()){ ?>
        <!--                <a class="fbutton btn-app"  href="javascript:MobAppSDKexecute('mobLoginGameCenter', {})" ><span>Game Center</span></a>-->
                <?php
                    if (empty($currentGame) || $this->Nav->showFunction('hide_login_google', $currentGame)) {
                       if(in_array($currentGame['id'],array('159'))){
                ?>
                   <?php if ($MobileDetect->isAndroidOS()){ ?>
                        <a class="btn-dgg"  href="javascript:MobAppSDKexecute('mobLoginGooglePlay', {})"><span>Google Play</span></a>
                    <?php }}} ?>
                <?php
        if (empty($currentGame) || $this->Nav->showFunction('hide_login_email', $currentGame)) {
            ?>
            <a class="fbutton btn-email"  href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login_email')) ?>">
                <?php
                if($currentGame['language_default'] == 'vie'){
                    $title_login_email  =   __('Email hoặc SĐT');
                }else{
                    $title_login_email  =   __('Đăng nhập bằng Email');
                }
                ?>
                <span> <?php echo $title_login_email ; ?></span>
                <br>
                <span class="text-medium"><?php echo __('Tài khoản MobID'); ?></span>
            </a>
        <?php } ?>
        <?php
        if (empty($currentGame) || $this->Nav->showFunction('hide_play_now', $currentGame)) {
            ?>
            <?php
            if (empty($currentGame) || $this->Nav->showFunction('hide_notice', $currentGame)) {
                ?>
                <a class="fbutton btn-playnow cd-popup-trigger"   href="javascript:void(0)" data-id="confirm_playnow">
                    <span><?php echo __('Chơi ngay') ?></span></button>
                </a>
            <?php }else{ ?>
                <a class="fbutton btn-playnow"   href="javascript:MobAppSDKexecute('mobLoginGuest', {})">
                    <span><?php echo __('Chơi ngay') ?></span></button>
                </a>
            <?php } ?>
        <?php } ?>

        <?php
        if (empty($currentGame) || $this->Nav->showFunction('enable_dashboard', $currentGame)) {
            ?>
            <p class="ftext rs"><?php echo __("Bạn sẽ cần nâng cấp tài khoản để tránh mất nhân vật khi cài lại game hoặc reset thiết bị."); ?></p>
        <?php } ?>
        <?php
        if(isset($currentGame['data']['display_logo_older']) && $currentGame['data']['display_logo_older'] == 1 ){
            if(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 0){
                ?>
                <p class="textAc rs"><img src="http://a.smobgame.com/plf/img/everyone.png" width="60" height="86" class="ghdt"></p>
            <?php }elseif(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 12){ ?>
                <p class="textAc rs"><img src="http://a.smobgame.com/plf/img/age12.png" width="60" height="86" class="ghdt"></p>
            <?php }elseif(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 18){ ?>
                <p class="textAc rs"><img src="http://a.smobgame.com/plf/img/older18.png" width="60" height="86" class="ghdt"></p>
            <?php } ?>
            <h4 style="text-align: center">Chơi quá 180 phút một ngày sẽ ảnh hưởng xấu đến sức khỏe </h4>
        <?php } ?>

    </article>
</section>
<div id="pop-confirm_playnow" class="modalDialog cd-popup">
    <div class="innertDialog">
        <div class="contentDialog">
            <h2><?php echo __('Đăng nhập nhanh'); ?></h2>
            <p><?php echo __('Dữ liệu gắn với tài khoản Chơi ngay sẽ bị xoá khi bạn xoá game hoặc cài lại máy. Hãy cân nhắc khi sử dụng hình thức đăng nhập này. An toàn hơn, hãy đăng nhập bằng Facebook hoặc FunID.'); ?></p>
        </div>
        <div class="actionDialog cf">
            <a href="javascript:MobAppSDKexecute('mobLoginGuest', {})" title="OK" class="btn accept">OK</a>
            <a href="javascript:void(0)" title="Close" class="btn close cd-popup-close">Close</a>
        </div>
    </div>

</div>
<script type='text/javascript'>
    jQuery(document).ready(function($){
        $('body').removeClass("info");
        //open popup
        $('.cd-popup-trigger').on('click', function(event){
            event.preventDefault();
            var id = $(this).data("id");
            $("#pop-"+id).addClass('is-visible');
        });
        //close popup
        $('.cd-popup').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
                event.preventDefault();
                $(this).removeClass('is-visible');
            }
        });

    });
</script>