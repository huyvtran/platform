<?php
    $email = $user['User']['email'];
    $email_null = 0;
    if($email == null || strpos($email, '@haitacmobi.com') !== false){
        $email = '';
        $email_null = 1;
    }else{
        $count  =   strlen($email);
        $email  =   substr($email,0,3).'****@***'.substr($email,$count-5,$count);
    }
    $class_email_verify = '';
    if($user['User']['email_verified'] && $email_null != 1){
        $class_email_verify = 'tt-done';
    }
    $phone = '';
    if(isset($user['User']['phone']) && $user['User']['phone'] != ''){
        $count_phone = strlen($user['User']['phone']);
        $phone = substr($user['User']['phone'],0,3).'*****'. substr($user['User']['phone'],$count_phone -3,$count_phone);
    }
    $ispop = array();
    if(isset($this->request->query['ispop'])){
        $ispop = array('ispop'=>true);
    }
?>
<?php if(isset($this->request->query['sent'])){ ?>
    <div class="tt-notice">
        <?php echo __("Một email xác thực đã được gửi tới hòm thư của bạn. Hãy kiểm tra email và làm theo hướng dẫn."); ?>
    </div>
<?php } ?>
<div class="thong-tin">
    <div class="box-ttMes">

<!--        --><?php //if($full == 0){?>
            <p class="rs">
                <?php echo __('Hãy hoàn thiện thông tin đăng nhập bằng cách tạo tài khoản FunID và nhận +5 FunCoin.'); ?>
            </p>

<!--        --><?php //} ?>
            <p class="rs"><?php echo __('Thông tin đăng nhập'); ?></p>

    </div>
    <ul class="box-ttList rs">
        <li>
            <a class="cf tt-id" href="javascript:void(0)">
                <span class="text">ID</span>
                <span class="dataT"><?php echo $user['User']['id'] ;?></span>
            </a>
        </li>
        <li>
            <?php if($notChanged == 1){ ?>
            <a class="cf tt-id" href="javascript:void(0)">
            <?php }else{ ?>
                <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'changeUsername','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
            <?php } ?>
                <span class="text"><?php echo __('Tên đăng nhập'); ?></span>
                <span class="dataT"><?php echo $user['User']['username']; ?></span>
            </a>
        </li>
        <?php if($email != '' && $user['User']['email_verified'] && !empty($user['User']['password'])){ ?>
        <li>
            <?php
            $phone_verify = '';
                if($phone != ''){
                    $phone_verify = 'tt-done';
                }
            $pre_phone = '84';
            if(!empty($profile['Profile']['phone_code'])){
                $pre_phone = $profile['Profile']['phone_code'];
            }
            ?>
            <div class="pRel">
                <?php if(empty($profile['Profile']['phone']) || $profile['Profile']['phone_verified'] == 0){
                    $link = $this->Html->url(array('controller'=>'UserInfors','action' => 'updatePhone','?'=>array_merge(array('isRedirect'=>true),$ispop)));
                }else{
                    $link = $this->Html->url(array('controller'=>'UserInfors','action' => 'confirmChangePhoneExist1','?'=>array_merge(array('isRedirect'=>true,'isoCode'=>$pre_phone),$ispop)));
                }
                ?>
                    <a class="cf tt-lb tt-xacthuc <?php echo $phone_verify; ?>" href="<?php echo $link; ?>">
                    <span class="text">Số điện thoại</span>
                    <span class="dataT"><?php echo $phone ;?></span>
                </a>
                <span class="text-nt">Đây cũng là SĐT bảo vệ tài khoản của bạn</span>
            </div>
        </li>
        <?php } ?>
        <li>
            <div class="pRel">
                <?php if($email != '' && !$user['User']['email_verified']){ ?>
                    <a class="cf tt-lb tt-xacthuc" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addEmail','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
                        <span class="text">Email đăng nhập</span>
                        <span class="dataT"><?php echo $email; ?></span>
                    </a>
                    <a class="xacthuc" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'verifyPinCodeEmail','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">Xác thực email</a>
                <?php }else{ ?>
                    <a class="cf tt-lb <?php echo $class_email_verify; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addEmail','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
                        <span class="text">Email đăng nhập</span>
                        <span class="dataT"><?php echo $email; ?></span>
                    </a>
                <?php } ?>
            </div>
        </li>
        <li>
            <?php
            $email = $user['User']['email'];
            if($email_null || $user['User']['email_verified'] == 0 && $user['User']['password'] == null){
            ?>
                <a class="cf tt-lb" href="javascript:void(0)" onclick="javascript:alert('Hãy nhập email đăng nhập hoặc hoàn thành xác thực  địa chỉ email đăng nhập trước khi tạo mật khẩu')">
                <?php }else{ ?>
                <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addPassword','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>">
                <?php } ?>
                    <span class="text">Mật khẩu</span>
                    <span class="dataT"><?php echo $user['User']['password'] !=null?'******':''; ?></span>
                </a>
        </li>
    </ul>
</div>