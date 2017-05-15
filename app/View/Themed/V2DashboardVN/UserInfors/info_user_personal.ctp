<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="thong-tin" id="wrapper">
    <div class="box-ttMes">
<!--        --><?php //if($full == 0){ ?>
        <p class="rs">
            Hãy hoàn thiện thông tin cá nhân giúp chúng tôi có thể liên hệ với bạn khi cần thiết. Sau khi hoàn thành nhận +5 FunCoin
        </p>
<!--        --><?php //} ?>
        <h2 class="rs"><?php echo __("Thông tin cá nhân"); ?></h2>
    </div>
    <ul class="box-ttList rs">
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal','?'=>$ispop)) ?>">
                <span class="text"><?php echo __("Họ tên"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['fullname'])?$profile['Profile']['fullname']:''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal','?'=>$ispop)) ?>">
                <span class="text"><?php echo __("Ngày sinh"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['birthday'])?date('d/m/Y',strtotime($profile['Profile']['birthday'])):''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal','?'=>$ispop)) ?>">
                <span class="text"><?php echo __("Giới tính"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['gender'])?$profile['Profile']['gender']:''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal','?'=>$ispop)) ?>">
                <span class="text"><?php echo __("Địa chỉ"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['address'])?$profile['Profile']['address']:''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateInfoPersonal','?'=>$ispop)) ?>">
                <span class="text"><?php echo __("Tỉnh/TP"); ?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['province'])?$profile['Profile']['province']:''; ?></span>
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
