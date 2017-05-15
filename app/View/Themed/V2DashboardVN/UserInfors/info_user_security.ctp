<?php
$cmnd = '';
if(isset($profile['Profile']['peopleId']) && $profile['Profile']['peopleId'] != ''){
    $count = strlen($profile['Profile']['peopleId']);
    $cmnd   =  substr($profile['Profile']['peopleId'],0,$count-4)."****";
}
    $email_contact = '';
    if(isset($profile['Profile']['email_contact']) && $profile['Profile']['email_contact'] != ''){
        $email = $profile['Profile']['email_contact'];
        $count  =   strlen($email);
        $email_contact  =   substr($profile['Profile']['email_contact'],0,3).'****@***'.substr($profile['Profile']['email_contact'],$count-5,$count);
    }
    $phone = '';
    if(isset($profile['Profile']['phone']) && $profile['Profile']['phone'] != ''){
        $count_phone = strlen($profile['Profile']['phone']);
        $phone = substr($profile['Profile']['phone'],0,3).'*****'. substr($profile['Profile']['phone'],$count_phone -3,$count_phone);
    }
    if($phone != ''){
        if($profile['Profile']['phone_verified'] == false){
            $verify_phone = "tt-xacthuc";
        }else{
            $verify_phone = "tt-done";
        }
    }else{
        $verify_phone = "";
        $pre_phone = '';
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
    $ispop = array();
    if(isset($this->request->query['ispop'])){
        $ispop = array('ispop'=>true);
    }
?>
<div class="thong-tin">
    <div class="box-ttMes">
        <p class="rs">
            <?php echo __("Hãy hoàn thiện thông tin bảo mật tài khoản để giúp bảo vệ trong trường hợp quên mật khẩu hoặc có tranh chấp.");?>
            <br>
            <?php echo __("Bạn được +5 FunCoin sau khi hoàn thành.")?>
        </p>
        <h2 class="rs"><?php echo __("Thông tin bảo mật tài khoản");?></h2>
    </div>
    <ul class="box-ttList rs">
        <li>
            <div class="pRel">
            <?php if($verify_email == "tt-xacthuc" || $verify_email == '' ){ ?>
                <a class="cf tt-lb <?php echo $verify_email; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateEmailSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
            <?php }else{ ?>
                    <a class="cf tt-lb <?php echo $verify_email; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendChangeEmailSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
            <?php } ?>
                    <span class="text"><?php echo __("Email bảo vệ");?></span>
                    <span class="dataT"><?php echo $email_contact; ?></span>
                </a>
                <?php if($verify_email == "tt-xacthuc"){ ?>
                    <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'confirmChangeEmailSecurity','?'=>array_merge(array('isRedirect'=>true,'email_contact'=>$profile['Profile']['email_contact']),$ispop))); ?>" class="xacthuc" onclick="">Xác thực email</a>
                <?php } ?>
            </div>
        </li>
        <li>
            <?php
            $class_add = '';
            if($verify_phone == "tt-xacthuc"){
                $class_add = 'tt-active';
            } ?>
            <div class="pRel <?php echo $class_add; ?>">
                <?php if($phone == '' || $verify_phone == "tt-xacthuc"){ ?>
                    <a class="cf tt-lb tt-xacthuc <?php echo $verify_phone; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updatePhone','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
                <?php }else{ ?>
                    <a class="cf tt-lb tt-xacthuc <?php echo $verify_phone; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'confirmChangePhoneExist1','?'=>array_merge(array('isRedirect'=>true,'isoCode'=>$pre_phone),$ispop))); ?>">
                <?php } ?>
                        <span class="text"><?php echo __("Số điện thoại");?></span>
                        <?php if($phone == ''){ ?>
                            <span class="dataT"><?php echo $phone; ?></span>
                        <?php }else{ ?>
                            <?php if($pre_phone == '84'){ ?>
                                <span class="dataT"><?php echo '(+'.$pre_phone .') ' . substr($phone,1,$count_phone); ?></span>
                            <?php }else{ ?>
                                <span class="dataT"><?php echo '(+'.$pre_phone .') ' . $phone; ?></span>
                            <?php } ?>
                        <?php } ?>
                    </a>
                        <span class="text-nt">Bạn có thể đăng nhập tài khoản bằng SĐT này</span>
                <?php if( $verify_phone == "tt-xacthuc"){ ?>
                    <a class="xacthuc" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'confirmPhoneSecurity1','?'=>array_merge(array('isRedirect'=>true,'isoCode'=>$pre_phone),$ispop))) ?>">Xác thực SĐT</a>
                <?php } ?>
            </div>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateCmt','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>">
                <span class="text"><?php echo __("Số CMND");?></span>
                <span class="dataT"><?php echo $cmnd; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateCmt','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>">
                <span class="text"><?php echo __("Ngày cấp");?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['peopleId_date_get']) && $profile['Profile']['peopleId_date_get'] != null?date('d/m/Y',strtotime($profile['Profile']['peopleId_date_get'])):''; ?></span>
            </a>
        </li>
        <li>
            <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'updateCmt','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>">
                <span class="text"><?php echo __("Nơi cấp");?></span>
                <span class="dataT"><?php echo isset($profile['Profile']['peopleId_place_get']) && $profile['Profile']['peopleId_place_get'] != null?$profile['Profile']['peopleId_place_get']:''; ?></span>
            </a>
        </li>
    </ul>
</div>