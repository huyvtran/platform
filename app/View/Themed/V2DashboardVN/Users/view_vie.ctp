<?php
$vip_current  = $vip   =   trim($user['User']['vip']);
$funCoin_bag =   $funcoin_wallet;
$funCoin     =   $funcoin_accumulate;
if(isset($user['User']['facebook_uid']) && $user['User']['facebook_uid'] != null ){
    $avatar =   'https://graph.facebook.com/'.$user['User']['facebook_uid'].'/picture?type=large';
}else{
    $avatar =   'http://a.smobgame.com/plf/uncommon/dashboard_v2/images/info-thumb.png';
}
if($vip_current == User::USER_VIP_DEFAULT){
    $stt    =   0;
    $price_vip_current  =   User::PRICE_USER_VIP_DEFAULT;
    $icon = 'infoNormal';
    $vip_current    = 'Hội Viên';
}elseif($vip_current == User::USER_VIP_SILVER_1){
    $stt    =   1;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_1;
    $icon = 'infoSilver1';
}elseif($vip_current == User::USER_VIP_SILVER_2){
    $stt    =   2;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_2;
    $icon = 'infoSilver2';
}elseif($vip_current == User::USER_VIP_SILVER_3){
    $stt    =   3;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_3;
    $icon = 'infoSilver3';
}elseif($vip_current == User::USER_VIP_GOLD_1){
    $stt    =   4;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_1;
    $icon = 'infoGold1';
}elseif($vip_current == User::USER_VIP_GOLD_2){
    $stt    =   5;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_2;
    $icon = 'infoGold2';
}elseif($vip_current == User::USER_VIP_GOLD_3){
    $stt    =   6;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_3;
    $icon = 'infoGold3';
}elseif($vip_current == User::USER_VIP_DIAMOND){
    $stt    =   7;
    $price_vip_current  =   User::PRICE_USER_VIP_DIAMOND;
    $icon = 'infoPalatium';
}else{
    $stt    =   0;
    $price_vip_current  =   User::PRICE_USER_VIP_DEFAULT;
    $icon = 'infoNormal';
    $vip_current    = 'Hội Viên';
}

// check funcoin crease
if ($funCoin >= User::PRICE_USER_VIP_DEFAULT && $funCoin < User::PRICE_USER_VIP_SILVER_1) {// hội viên
    $stt_funcoin = 1;
    $stt_funcoin_g = 0;
    $next_vip = User::USER_VIP_SILVER_1;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_1;
    $pre_vip  = User::USER_VIP_DEFAULT;
    $price_pre_vip = User::PRICE_USER_VIP_DEFAULT;
} elseif ($funCoin >= User::PRICE_USER_VIP_SILVER_1 && $funCoin < User::PRICE_USER_VIP_SILVER_2) {// silver 1
    $stt_funcoin = 2;
    $stt_funcoin_g = 1;
    $next_vip = User::USER_VIP_SILVER_2;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_2;
    $pre_vip  = User::USER_VIP_SILVER_1;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_1;
} elseif ($funCoin >= User::PRICE_USER_VIP_SILVER_2 && $funCoin < User::PRICE_USER_VIP_SILVER_3) {// silver 2
    $stt_funcoin = 3;
    $stt_funcoin_g = 2;
    $next_vip = User::USER_VIP_SILVER_3;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_3;
    $pre_vip  = User::USER_VIP_SILVER_2;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_2;
} elseif ($funCoin >= User::PRICE_USER_VIP_SILVER_3 && $funCoin < User::PRICE_USER_VIP_GOLD_1) { // silver 3
    $stt_funcoin = 4;
    $stt_funcoin_g = 3;
    $next_vip = User::USER_VIP_GOLD_1;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_1;
    $pre_vip  = User::USER_VIP_SILVER_3;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_3;
} elseif ($funCoin >= User::PRICE_USER_VIP_GOLD_1 && $funCoin < User::PRICE_USER_VIP_GOLD_2) {// gold 1
    $stt_funcoin = 5;
    $stt_funcoin_g = 4;
    $next_vip = User::USER_VIP_GOLD_2;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_2;
    $pre_vip  = User::USER_VIP_GOLD_1;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_1;
} elseif ($funCoin >= User::PRICE_USER_VIP_GOLD_2 && $funCoin < User::PRICE_USER_VIP_GOLD_3) {// gold 2
    $stt_funcoin = 6;
    $stt_funcoin_g = 5;
    $next_vip = User::USER_VIP_GOLD_3;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_3;
    $pre_vip  = User::USER_VIP_GOLD_2;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_2;
} elseif ($funCoin >= User::PRICE_USER_VIP_GOLD_3 && $funCoin < User::PRICE_USER_VIP_DIAMOND) { // gold 3
    $stt_funcoin = 7;
    $stt_funcoin_g = 6;
    $next_vip = User::USER_VIP_DIAMOND;
    $price_next_vip = User::PRICE_USER_VIP_DIAMOND;
    $pre_vip  = User::USER_VIP_GOLD_3;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_3;
} elseif ($funCoin >= User::PRICE_USER_VIP_DIAMOND) {
    $stt_funcoin = 8;
    $stt_funcoin_g = 6;
    $next_vip = User::USER_VIP_DIAMOND;
    $price_next_vip = User::PRICE_USER_VIP_DIAMOND;
    $pre_vip  = User::USER_VIP_DIAMOND;
    $price_pre_vip = User::PRICE_USER_VIP_DIAMOND;
}
//funcoin increase
// caculate % funcoin
$class_stand = '';
if($funCoin >= $price_vip_current && $vip != User::USER_VIP_DEFAULT){
    if($stt_funcoin - $stt >=2){
        $percent = (($funCoin - $price_vip_current )/($price_next_vip - $price_vip_current))*100 .'%';
        if($funCoin > $price_pre_vip){
            $class_stand    =   'infoProcessThree';
        }
    }else{
        $percent = (($funCoin - $price_vip_current )/($price_next_vip - $price_pre_vip))*100 .'%';
    }

    if($percent > 100){
        $percent   =   '100%';
    }

    if($price_pre_vip == $funCoin){
        $class_stand = 'infoProcessPer';
    }
}elseif($funCoin < $price_vip_current && $vip != User::USER_VIP_DEFAULT){
    $percent = (($funCoin - $price_pre_vip)/($price_vip_current - $price_pre_vip))*100 .'%';
    if($percent > 100){
        $percent = '100%';
    }

    if($price_next_vip == $funCoin){
        $class_stand = 'infoProcessPer';
    }
}elseif($vip == User::USER_VIP_DEFAULT){
    if($stt_funcoin - $stt >=2){

        $percent = (($funCoin - $price_vip_current )/($price_next_vip - $price_vip_current))*100 .'%';
        if($funCoin > $price_pre_vip){
            $class_stand    =   'infoProcessThree';
        }
        if($price_pre_vip == $funCoin){
            $class_stand = 'infoProcessPer';
        }
    }else{
        $percent = (($funCoin - $price_vip_current )/($price_next_vip - $price_pre_vip))*100 .'%';
    }
}
if($percent == 0 ){
    $class_stand    =   'infoProcessNone';
}
if($stt_funcoin == 8){
    $class_stand    =   'infoProcessFull';
}
$class_verify_info = '';
if($check_info != 1){
    $class_verify_info = 'infoW';
}
$class_verify_login =   '';
$check_info = 1;
if($user['User']['password'] == null || $user['User']['email'] == null || $user['User']['email_verified'] == 0 || empty($user['User']['phone'])){
    $class_verify_login = 'infoW';
}
$class_verify_security = '';

if(!$check_security || (isset($profile["Profile"]['phone_verified']) && $profile["Profile"]['phone_verified'] != true) ||
    (isset($profile["Profile"]['email_contact_verified']) && $profile["Profile"]['email_contact_verified'] != true )){
    $class_verify_security = 'infoW';
}
$phone_email_security_verify = false;
if((isset($profile["Profile"]['phone_verified']) && $profile["Profile"]['phone_verified'] == true) &&
    (isset($profile["Profile"]['email_contact_verified']) && $profile["Profile"]['email_contact_verified'] == true )){
    $phone_email_security_verify = true;
}
$date_next_month  =  date("m/Y", strtotime("+1 month", time()) );
$date_pre_6_month  =  date("m/Y", strtotime("-6 month", time()) );
$date_pre_5_month  =  date("m/Y", strtotime("-5 month", time()) );
$day    =   date('d/m/Y');
$day_array = explode('/',$day);
$day_r   =   '08';
if( $day_array[0] >= '09') {
    $date_start =   '09/'.$date_pre_5_month;
    $date_run = $day_r . '/' . $date_next_month ;
}else{
    $date_start =   '09/'.$date_pre_6_month;
    $date_run = $day_r . '/' . $day_array[1].'/'. $day_array[2];
}
?>
<section id="wrapper">
<div class="content-wrap">
<div class="infoVip">
    <div class="infoName">
        <span class="infoThumb" style="background-image: url(<?php echo $avatar; ?>) "></span>
        <a href="javascript:void(0)" class="infoUserName">
            <?php echo $user['User']['username']; ?>
        </a>
        <span class="infoId">ID: <?php if(!empty($user['User']['id'])) echo $user['User']['id']; ?></span>
    </div>
    <?php   if (empty( $game['Game']) || $this->Nav->showFunction('hide_user_info',  $game['Game'])) {?>
        <div class="infoMedal cf">
            <a href="javascript:void(0);" onclick="url_loyer()" class="btnCup">
                <!--nocache-->
                <span class="text-i"><span class="infoIco infoCup <?php echo $icon; ?>"></span><?php echo $vip_current; ?></span>
                <!--/nocache-->
                <span class="infoText"><?php echo __('Hạng thành viên'); ?></span>
            </a>
            <a class="btnCoin" href="<?php echo $this->Html->url(array( 'action' => 'historyFunPoint')) ?>">
                <span class="text-i"><span class="infoFP cspr infoIco"></span><?php echo $funCoin_bag; ?></span>
                <span class="infoText"><?php echo __('FunCoin hiện có'); ?><i class="spr i-hoi"></i></span>
            </a>
        </div>
        <?php if($vip_current != User::USER_VIP_DIAMOND){ ?>
            <div class="infoScore box-lvup">
                <p class="rs"><?php echo 'Tổng FunCoin tích luỹ từ '.$date_start.'  - '.$date_run; ?></p>
                <div class="infoProcess <?php echo $class_stand; ?>">
                    <?php if($funCoin >= $price_vip_current){?>
                        <?php if($stt_funcoin == 8){ ?>
                            <div class="infoHang1 infoHangN">
                                <span class="infoNameH">Platium</span>
                                <span class="infoNameC">100k</span>
                            </div>

                            <div class="infoProcessInner">
                                <div class="fakeProcess" style="width: 100%"></div>
                            </div>
                        <?php }else{ ?>
                            <?php if($stt_funcoin - $stt >=2){ ?>
                                <div class="infoHang1 infoHangN">
                                    <span class="infoNameH"><?php echo $vip_current; ?></span>
                                    <span class="infoNameC"><?php echo $price_vip_current; ?></span>
                                </div>
                                <?php if($stt_funcoin - $stt >=3){ ?>
                                    <div class="infoHang4">
                                        <span class="infoNameH">. . .</span>
                                    </div>
                                <?php } ?>
                                <div class="infoHang3 ">
                                    <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_pre_vip; ?></span>
                                </div>
                                <div class="infoProcessInner">
                                    <div class="fakeProcess" style="width: <?php echo $percent;?>">
                                        <span class="infoNameCN"><?php echo $funCoin; ?></span>
                                    </div>
                                </div>
                                <div class="infoHang2">
                                    <span class="infoNameH"><?php echo $next_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_next_vip; ?></span>
                                </div>
                            <?php }else{ ?>
                                <div class="infoHang1 infoHangN">
                                    <span class="infoNameH"><?php echo $vip_current; ?></span>
                                    <span class="infoNameC"><?php echo $price_vip_current; ?></span>
                                </div>
                                <div class="infoProcessInner">
                                    <div class="fakeProcess" style="width: <?php echo $percent;?>">
                                        <span class="infoNameCN"><?php echo $funCoin; ?></span>
                                    </div>
                                </div>
                                <div class="infoHang2">
                                    <span class="infoNameH"><?php echo $next_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_next_vip; ?></span>
                                </div>
                            <?php }} ?>
                    <?php }else{
                        ?>
                        <?php if($stt - $stt_funcoin_g >=2){ ?>
                            <div class="infoProcess infoProcessRed" >
                                <div class="infoHang1">
                                    <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_pre_vip; ?></span>
                                </div>
                                <div class="infoHang3">
                                    <span class="infoNameH"><?php echo $next_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_next_vip; ?></span>
                                </div>
                                <div class="infoProcessInner">
                                    <div class="fakeProcess" style="width: <?php echo $percent;?>">
                                        <span class="infoNameCN"><?php echo $funCoin; ?></span>
                                    </div>
                                </div>
                                <div class="infoHang2 infoHangN">
                                    <span class="infoNameH"><?php echo $vip_current; ?></span>
                                    <span class="infoNameC"><?php echo $price_vip_current; ?></span>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="infoProcess infoProcessRed" >
                                <div class="infoHang1">
                                    <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                    <span class="infoNameC"><?php echo $price_pre_vip; ?></span>
                                </div>
                                <div class="infoProcessInner">
                                    <div class="fakeProcess" style="width: <?php echo $percent;?>">
                                        <span class="infoNameCN"><?php echo $funCoin; ?></span>
                                    </div>
                                </div>
                                <div class="infoHang2 infoHangN">
                                    <span class="infoNameH"><?php echo $vip_current; ?></span>
                                    <span class="infoNameC"><?php echo $price_vip_current; ?></span>
                                </div>
                            </div>
                        <?php }} ?>
                </div>
            </div>
        <?php }} ?>
</div>
<?php   if (empty( $game['Game']) || $this->Nav->showFunction('hide_user_info',  $game['Game'])) {?>
<div class="infoContent">
    <ul class="rs">
        <li>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin')) ?>" class="infoLogin <?php echo $class_verify_login; ?>">
                <i class="infoIco spr"></i>
                <span class="infoTitle"><?php echo __('Thông tin đăng nhập'); ?></span>
                <span class="infoNote"><?php echo __('Quản lý thông tin dùng để đăng nhập') ; ?></span>
            </a>
        </li>
        <li>
            <?php if(($user['User']['email'] == null || $user['User']['password'] == null) && $user['User']['facebook_uid'] == null ){ ?>
            <a href="javascript:void(0)" data-id="confirm_playnow" class="infoUser fbutton btn-playnow cd-popup-trigger <?php echo $class_verify_info; ?>">
                <?php }else{ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal')) ?>" class="infoUser <?php echo $class_verify_info; ?>">
                    <?php } ?>
                    <i class="infoIco spr"></i>
                    <span class="infoTitle"><?php echo __('Thông tin cá nhân') ; ?></span>
                    <span class="infoNote"><?php echo __('Xem và cập nhật thông tin cá nhân'); ?></span>
                </a>
        </li>
        <li>
            <?php if(($user['User']['email'] == null || $user['User']['password'] == null) && $user['User']['facebook_uid'] == null){ ?>
            <a href="javascript:void(0)" data-id="confirm_playnow" class="infoUser fbutton btn-playnow cd-popup-trigger <?php echo $class_verify_info; ?>">
                <?php }else{ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity')) ?>" class="infoProtect <?php echo $class_verify_security; ?>">
                    <?php } ?>
                    <i class="infoIco spr"></i>
                    <span class="infoTitle"><?php echo __('Bảo vệ tài khoản') ; ?></span>
                    <span class="infoNote"><?php echo __('Hỗ trợ bảo vệ tài khoản'); ?></span>
                </a>
        </li>
        <?php if($this->Session->read('Auth.User.facebook_uid')){ ?>
            <li class="infoListFb">
                <a href="javascript:void(0)" class="infoConnect">
                    <i class="infoIco spr"></i>
                    <span class="infoTitle"><?php echo __('Đã kết nối Faceboook') ;?></span>
                    <i class="infoIco verify spr"></i>
                </a>
            </li>
        <?php }else{ ?>
            <li class="infoListFb ">
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'connect_facebook')) ?>" class="infoConnect infoNotConnect">
                    <i class="infoIco spr"></i>
                    <span class="infoTitle"><?php echo __('Nối tài khoản với Facebook ngay!');?></span>
                    <i class="infoIco verify spr"></i>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
<?php   if (empty( $game['Game']) || $this->Nav->showFunction('hide_mission',  $game['Game'])) {?>
    <?php if($this->request->header('mobgame-sdk-version') != false && $this->request->header('mobgame-sdk-version') >='2.3.0' ){ ?>
        <div class="infoQuest">
            <ul class="rs">
                <li>
                    <a href="<?php echo $this->Html->url(array( 'action' => 'mission')) ?>" class="infoQ">
                        <i class="infoIco spr"></i>
                        <span class="infoTitle"><?php echo __('Nhiệm vụ của tôi');?></span>
                        <span class="infoNote"><?php echo __('Thực hiện nhiệm vụ nhận FunCoin.'); ?> </span>
                    </a>
                </li>
            </ul>
        </div>
    <?php }} ?>
</div>
<?php } ?>
</section>
<div id="pop-confirm_playnow" class="modalDialog cd-popup">
    <div class="innertDialog">
        <div class="contentDialog">
            <h2><?php echo __('Thông báo'); ?></h2>
            <p><?php echo 'Bạn cần tạo tài khoản FunID (email đăng nhập & mật khẩu) trước khi thực hiện hành động này'; ?></p>
        </div>
        <div class="actionDialog cf">
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin')); ?>" title="OK" class="btn accept">Tiếp tục</a>
            <a href="javascript:void(0)" title="Close" class="btn close cd-popup-close">Đóng</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function wrong_info(){
        alert('Bạn cần hoàn thiện email và SĐT bảo mật để được tham gia chương trình.');
//        window.location.href = "<?php //echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity')); ?>//";
    }
    function url_loyer(){
        window.location.href = "http://funtap.vn/khach-hang-than-thiet";
    }
</script>
<script type='text/javascript'>
    jQuery(document).ready(function($){
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