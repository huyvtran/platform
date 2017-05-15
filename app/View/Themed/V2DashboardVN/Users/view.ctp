<?php
$vip_current  = $vip   =   trim($user['User']['vip_en']);
if(isset($user['User']['facebook_uid']) && $user['User']['facebook_uid'] != null ){
    $avatar =   'https://graph.facebook.com/'.$user['User']['facebook_uid'].'/picture?type=large';
}else{
    $avatar =   'http://a.smobgame.com/plf/uncommon/dashboard_v2/images/info-thumb.png';
}
$class_below_gold = '';
if($vip_current == User::USER_VIP_DEFAULT){
    $stt    =   0;
    $price_vip_current  =   User::PRICE_USER_VIP_DEFAULT;
    $price_vip_current_title  =   User::PRICE_USER_VIP_DEFAULT;
    $icon = 'infoNormal';
}elseif($vip_current == User::USER_VIP_SILVER_1){
    $stt    =   1;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_1_EN;
    $price_vip_current_title  =   '1K';
    $icon = 'infoSilver1';
}elseif($vip_current == User::USER_VIP_SILVER_2){
    $stt    =   2;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_2_EN;
    $price_vip_current_title  =   '4K';
    $icon = 'infoSilver2';
}elseif($vip_current == User::USER_VIP_SILVER_3){
    $stt    =   3;
    $price_vip_current  =   User::PRICE_USER_VIP_SILVER_3_EN;
    $price_vip_current_title  =   '10K';
    $icon = 'infoSilver3';
}elseif($vip_current == User::USER_VIP_GOLD_1){
    $stt    =   4;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_1_EN;
    $price_vip_current_title  =   '20K';
    $icon = 'infoGold1';
}elseif($vip_current == User::USER_VIP_GOLD_2){
    $stt    =   5;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_2_EN;
    $price_vip_current_title  =   '50K';
    $icon = 'infoGold2';
    $class_below_gold = 'fake3';
}elseif($vip_current == User::USER_VIP_GOLD_3){
    $stt    =   6;
    $price_vip_current  =   User::PRICE_USER_VIP_GOLD_3_EN;
    $price_vip_current_title  =   '100K';
    $icon = 'infoGold3';
    $class_below_gold = 'fake3';
}elseif($vip_current == User::USER_VIP_DIAMOND){
    $stt    =   7;
    $price_vip_current  =   User::PRICE_USER_VIP_DIAMOND_EN;
    $price_vip_current_title  =   '200K';
    $icon = 'infoPalatium';
    $class_below_gold = 'fake3';
}else{
    $stt    =   0;
    $price_vip_current  =   User::PRICE_USER_VIP_DEFAULT;
    $icon = 'infoNormal';
    $vip_current    =  User::USER_VIP_DEFAULT;
    $price_vip_current_title  =   User::PRICE_USER_VIP_DEFAULT;
}

// check funcoin crease
if ($point >= User::PRICE_USER_VIP_DEFAULT && $point <= User::PRICE_USER_VIP_SILVER_1_EN) {// hội viên
    $stt_funcoin = 1;
    $stt_funcoin_g = 0;
    $next_vip = User::USER_VIP_SILVER_1;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_1_EN;
    $price_next_vip_title = '1K';
    $pre_vip  = User::USER_VIP_DEFAULT;
    $price_pre_vip = User::PRICE_USER_VIP_DEFAULT;
    $price_pre_vip_title = User::PRICE_USER_VIP_DEFAULT;
} elseif ($point > User::PRICE_USER_VIP_SILVER_1_EN && $point <= User::PRICE_USER_VIP_SILVER_2_EN) {// silver 1
    $stt_funcoin = 2;
    $stt_funcoin_g = 1;
    $next_vip = User::USER_VIP_SILVER_2;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_2_EN;
    $price_next_vip_title = '4K';
    $pre_vip  = User::USER_VIP_SILVER_1;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_1;
    $price_pre_vip_title = '1K';
} elseif ($point > User::PRICE_USER_VIP_SILVER_2_EN && $point <= User::PRICE_USER_VIP_SILVER_3_EN) {// silver 2
    $stt_funcoin = 3;
    $stt_funcoin_g = 2;
    $next_vip = User::USER_VIP_SILVER_3;
    $price_next_vip = User::PRICE_USER_VIP_SILVER_3_EN;
    $price_next_vip_title = '10K';
    $pre_vip  = User::USER_VIP_SILVER_2;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_2_EN;
    $price_pre_vip_title = '4K';
} elseif ($point > User::PRICE_USER_VIP_SILVER_3_EN && $point <= User::PRICE_USER_VIP_GOLD_1_EN) { // silver 3
    $stt_funcoin = 4;
    $stt_funcoin_g = 3;
    $next_vip = User::USER_VIP_GOLD_1;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_1_EN;
    $price_next_vip_title = '20K';
    $pre_vip  = User::USER_VIP_SILVER_3;
    $price_pre_vip = User::PRICE_USER_VIP_SILVER_3_EN;
    $price_pre_vip_title = '10K';
} elseif ($point > User::PRICE_USER_VIP_GOLD_1_EN && $point <= User::PRICE_USER_VIP_GOLD_2_EN) {// gold 1
    $stt_funcoin = 5;
    $stt_funcoin_g = 4;
    $next_vip = User::USER_VIP_GOLD_2;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_2_EN;
    $price_next_vip_title = '50K';
    $pre_vip  = User::USER_VIP_GOLD_1;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_1_EN;
    $price_pre_vip_title = '20K';
} elseif ($point > User::PRICE_USER_VIP_GOLD_2_EN && $point <= User::PRICE_USER_VIP_GOLD_3_EN) {// gold 2
    $stt_funcoin = 6;
    $stt_funcoin_g = 5;
    $next_vip = User::USER_VIP_GOLD_3;
    $price_next_vip = User::PRICE_USER_VIP_GOLD_3_EN;
    $price_next_vip_title = '100K';
    $pre_vip  = User::USER_VIP_GOLD_2;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_2_EN;
    $price_pre_vip_title = '50K';
} elseif ($point > User::PRICE_USER_VIP_GOLD_3_EN && $point <= User::PRICE_USER_VIP_DIAMOND_EN) { // gold 3
    $stt_funcoin = 7;
    $stt_funcoin_g = 6;
    $next_vip = User::USER_VIP_DIAMOND;
    $price_next_vip = User::PRICE_USER_VIP_DIAMOND_EN;
    $price_next_vip_title = '200K';
    $pre_vip  = User::USER_VIP_GOLD_3;
    $price_pre_vip = User::PRICE_USER_VIP_GOLD_3_EN;
    $price_pre_vip_title = '100K';
} elseif ($point > User::PRICE_USER_VIP_DIAMOND_EN) {
    $stt_funcoin = 8;
    $stt_funcoin_g = 6;
    $next_vip = User::USER_VIP_DIAMOND;
    $price_next_vip = User::PRICE_USER_VIP_DIAMOND_EN;
    $price_next_vip_title = '200K';
    $pre_vip  = User::USER_VIP_DIAMOND;
    $price_pre_vip = User::PRICE_USER_VIP_DIAMOND_EN;
    $price_pre_vip_title = '200K';
}
//funcoin increase
// caculate % funcoin
$class_stand = '';
if($point >= $price_vip_current && $vip != User::USER_VIP_DEFAULT){
    if($stt_funcoin - $stt >=2){
        $percent = (($point - $price_vip_current )/($price_next_vip - $price_vip_current))*100 .'%';
        if($point > $price_pre_vip){
            $class_stand    =   'infoProcessThree';
        }
    }else{
        $percent = (($point - $price_vip_current )/($price_next_vip - $price_pre_vip))*100 .'%';
    }

    if($percent > 100){
        $percent   =   '100%';
    }

    if($price_pre_vip == $point){
        $class_stand = 'infoProcessPer';
    }
}elseif($point < $price_vip_current && $vip != User::USER_VIP_DEFAULT){
    $percent = (($point - $price_pre_vip)/($price_vip_current - $price_pre_vip))*100 .'%';
    if($percent > 100){
        $percent = '100%';
    }

    if($price_next_vip == $point){
        $class_stand = 'infoProcessPer';
    }
}elseif($vip == User::USER_VIP_DEFAULT){
    if($stt_funcoin - $stt >=2){
        $percent = (($point - $price_vip_current )/($price_next_vip - $price_vip_current))*100 .'%';
        if($point > $price_pre_vip){
            $class_stand    =   'infoProcessThree';
        }
        if($price_pre_vip == $point){
            $class_stand = 'infoProcessPer';
        }
    }else{
        $percent = (($point - $price_vip_current )/($price_next_vip - $price_pre_vip))*100 .'%';
    }
}
if($percent == 0 ){
    $class_stand    =   'infoProcessNone';
}
if($stt_funcoin == 8){
    $class_stand    =   'infoProcessFull';
}
$class_verify_info = '';
if($check_info != 1 || empty($profile['Profile']['email_contact']) || $profile['Profile']['email_contact_verified'] == 0){
    $class_verify_info = 'infoW';
}
$class_verify_login =   '';
$check_info = 1;
if($user['User']['password'] == null || $user['User']['email'] == null || $user['User']['email_verified'] == 0){
    $class_verify_login = 'infoW';
}
$class_verify_security = '';
$date_next_month  =  date("m-Y", strtotime("+1 month", time()) );
$date_pre_6_month  =  date("m-Y", strtotime("-6 month", time()) );
$date_pre_5_month  =  date("m-Y", strtotime("-5 month", time()) );
$day    =   date('d-m-Y');
$day_array = explode('-',$day);
$day_r   =   '04';
if( $day_array[0] >= '05') {
    $date_start =   date('m/d/Y',strtotime('05-'.$date_pre_5_month));
    $date_run = date('m/d/Y',strtotime($day_r . '-' . $date_next_month)) ;
}else{
    $date_start =   date('m/d/Y',strtotime('05-'.$date_pre_6_month));
    $date_run = date('m/d/Y',strtotime($day_r . '-' . $day_array[1].'-'. $day_array[2]));
}
if($vip_current == 'Default'){
    $vip_current = 'Member';
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
                    <a href="javascript:void()" onclick="url_loyer()" class="btnCup">
                        <!--nocache-->
                        <span class="text-i"><span class="infoIco infoCup <?php echo $icon; ?>"></span><?php echo $vip_current; ?></span>
                        <!--/nocache-->
                        <span class="infoText"><?php echo __('Rank'); ?></span>
                    </a>
                    <a class="btnCoin" href="<?php echo $this->Html->url(array( 'action' => 'historyPoint')) ?>">
                        <?php
                        $point_title = $point;
                        if(strlen($point) > 3){
                            $point_title = substr($point,0,strlen($point) - 3).'.'. substr($point,strlen($point) - 3,strlen($point));
                            if(strlen($point) > 6){
                                $point_title = substr($point,0,strlen($point) - 6).'.'. substr($point,strlen($point) - 6,3) .'.'. substr($point,strlen($point) - 3,strlen($point));
                            }
                        }
                        ?>
                        <span class="text-i"><span class="infoFP cspr infoIco"></span><?php echo $point_title; ?></span>
                        <span class="infoText"><?php echo "Points"; ?><i class="spr i-hoi"></i></span>
                    </a>
                </div>
                <?php if($vip_current != User::USER_VIP_DIAMOND){ ?>
                    <div class="infoScore box-lvup">
                        <p class="rs"><?php echo 'Points accumulated from: '.$date_start.'  to '.$date_run; ?></p>
                        <div class="infoProcess <?php echo $class_stand; ?>">
                            <?php if($point >= $price_vip_current){?>
                                <?php if($stt_funcoin == 8){ ?>
                                    <div class="infoHang1 infoHangN">
                                        <span class="infoNameH">Platium</span>
                                        <span class="infoNameC">100k</span>
                                    </div>

                                    <div class="infoProcessInner">
                                        <div class="fakeProcess <?php echo $class_below_gold; ?>" style="width: 100%"></div>
                                    </div>
                                <?php }else{ ?>
                                    <?php if($stt_funcoin - $stt >=2){
                                        ?>
                                        <div class="infoHang1 infoHangN">
                                            <span class="infoNameH"><?php echo $vip_current; ?></span>
                                            <span class="infoNameC"><?php echo $price_vip_current_title; ?></span>
                                        </div>
                                        <?php if($stt_funcoin - $stt >=3){ ?>
                                            <div class="infoHang4">
                                                <span class="infoNameH">. . .</span>
                                            </div>
                                        <?php } ?>
                                        <div class="infoHang3 ">
                                            <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_pre_vip_title; ?></span>
                                        </div>
                                        <div class="infoProcessInner">
                                            <div class="fakeProcess <?php echo $class_below_gold; ?>" style="width: <?php echo $percent;?>">
                                                <span class="infoNameCN"><?php echo $point; ?></span>
                                            </div>
                                        </div>
                                        <div class="infoHang2">
                                            <span class="infoNameH"><?php echo $next_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_next_vip_title; ?></span>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="infoHang1 infoHangN">
                                            <span class="infoNameH"><?php echo $vip_current; ?></span>
                                            <span class="infoNameC"><?php echo $price_vip_current_title; ?></span>
                                        </div>
                                        <div class="infoProcessInner">
                                            <div class="fakeProcess <?php echo $class_below_gold; ?>" style="width: <?php echo $percent;?>">
                                                <span class="infoNameCN"><?php echo $point; ?></span>
                                            </div>
                                        </div>
                                        <div class="infoHang2">
                                            <span class="infoNameH"><?php echo $next_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_next_vip_title; ?></span>
                                        </div>
                                    <?php }} ?>
                            <?php }else{
                                ?>
                                <?php if($stt - $stt_funcoin_g >=2){ ?>
                                    <div class="infoProcess infoProcessRed">
                                        <div class="infoHang1">
                                            <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_pre_vip_title; ?></span>
                                        </div>
                                        <div class="infoHang3">
                                            <span class="infoNameH"><?php echo $next_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_next_vip_title; ?></span>
                                        </div>
                                        <div class="infoProcessInner">
                                            <div class="fakeProcess <?php echo $class_below_gold; ?>" style="width: <?php echo $percent;?>">
                                                <span class="infoNameCN"><?php echo $point; ?></span>
                                            </div>
                                        </div>
                                        <div class="infoHang2 infoHangN">
                                            <span class="infoNameH"><?php echo $vip_current; ?></span>
                                            <span class="infoNameC"><?php echo $price_vip_current_title; ?></span>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="infoProcess infoProcessRed <?php echo $class_below_gold; ?>" >
                                        <div class="infoHang1">
                                            <span class="infoNameH"><?php echo $pre_vip; ?></span>
                                            <span class="infoNameC"><?php echo $price_pre_vip_title; ?></span>
                                        </div>
                                        <div class="infoProcessInner">
                                            <div class="fakeProcess <?php echo $class_below_gold; ?>" style="width: <?php echo $percent;?>">
                                                <span class="infoNameCN"><?php echo $point; ?></span>
                                            </div>
                                        </div>
                                        <div class="infoHang2 infoHangN">
                                            <span class="infoNameH"><?php echo $vip_current; ?></span>
                                            <span class="infoNameC"><?php echo $price_vip_current_title; ?></span>
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
                        <span class="infoTitle"><?php echo 'Login information'; ?></span>
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
                            <span class="infoTitle"><?php echo __('Nối tài khoản với Facebook');?></span>
                            <i class="infoIco verify spr"></i>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
</section>
<div id="pop-confirm_playnow" class="modalDialog cd-popup">
    <div class="innertDialog">
        <div class="contentDialog">
            <h2><?php echo __('Notice'); ?></h2>
            <p><?php echo 'You need to create an account FunID (email login & password) before executing this action'; ?></p>
        </div>
        <div class="actionDialog cf">
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin')); ?>" title="OK" class="btn accept">OK</a>
            <a href="javascript:void(0)" title="Close" class="btn close cd-popup-close">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function url_loyer(){
        window.location.href = "http://mobgame.mobi/loyalty-program";
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
