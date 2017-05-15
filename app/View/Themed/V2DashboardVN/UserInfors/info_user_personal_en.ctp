<?php
$email_contact = '';
if(isset($profile['Profile']['email_contact']) && $profile['Profile']['email_contact'] != ''){
    $email = $profile['Profile']['email_contact'];
    $count  =   strlen($email);
    $email_contact  =   substr($profile['Profile']['email_contact'],0,3).'****@***'.substr($profile['Profile']['email_contact'],$count-5,$count);
}
if($email_contact != ''){
    if($profile['Profile']['email_contact_verified'] == false || $profile['Profile']['email_contact_verified'] == null ){
        $verify_email = "tt-xacthuc";
    }else{
        $verify_email = "tt-done";
    }
}else{
    $verify_email = "";
}

?>
<div class="thong-tin" id="wrapper">
    <div class="box-ttMes">
        <h2 class="rs"><?php echo __("Thông tin cá nhân"); ?></h2>
    </div>
    <ul class="box-ttList rs">
        <li>
            <div class="pRel">
                <?php if($verify_email == "tt-xacthuc" || $verify_email == '' ){ ?>
                <a class="cf tt-lb <?php echo $verify_email; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateEmailSecurity','?'=>array('isRedirect'=>true))); ?>">
                    <?php }else{ ?>
                    <a class="cf tt-lb <?php echo $verify_email; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendChangeEmailSecurity','?'=>array('isRedirect'=>true))); ?>">
                        <?php } ?>
                        <span class="text"><?php echo __("Email bảo vệ");?></span>
                        <span class="dataT"><?php echo $email_contact; ?></span>
                    </a>
                    <?php if($verify_email == "tt-xacthuc"){ ?>
                        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'confirmChangeEmailSecurity','?'=>array('isRedirect'=>true,'email_contact'=>$profile['Profile']['email_contact']))); ?>" class="xacthuc" onclick=""><?php echo __("Xác thực email");?></a>
                    <?php } ?>
            </div>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal')) ?>">
                <span class="text"><?php echo __("Họ tên"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['fullname'])?$profile['Profile']['fullname']:''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal')) ?>">
                <span class="text"><?php echo __("Ngày sinh"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['birthday'])?date('m/d/Y',strtotime($profile['Profile']['birthday'])):''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal')) ?>">
                <span class="text"><?php echo __("Giới tính"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['gender'])?__($profile['Profile']['gender']):''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal')) ?>">
                <span class="text"><?php echo __("Địa chỉ"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['address'])?$profile['Profile']['address']:''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal')) ?>">
                <span class="text"><?php echo __("Country"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['country'])?$profile['Profile']['country']:''; ?></span>
            </a>
        </li>
    </ul>
</div>
<div class="box-scr" id="backTop"><span class="unu"></span> <span class="doi"></span> <span class="trei"></span> </div>
<script>
    $(window).load(function() {
        if ($(window).height() > 480) {
            $('#backTop').fadeOut();
        }
    });
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            jQuery('#backTop').fadeOut();
        }else {
            jQuery('#backTop').fadeIn();
        }
    });
    var $elem = $('#wrapper');
    $('#backTop').click(
        function (e) {
            $('html, body').animate({scrollTop: $elem.height()}, 800);
        }
    );
</script>
