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
?>
<div class="thong-tin">
    <div class="box-ttMes">
        <p class="rs">
            <?php echo "Login information"; ?>
        </p>
    </div>
    <ul class="box-ttList rs">
        <li>
            <a class="cf tt-id" href="javascript:void(0)">
                <span class="text"><?php echo __('ID'); ?></span>
                <span class="dataT"><?php echo $user['User']['id'] ;?></span>
            </a>
        </li>
        <li>
            <?php if($notChanged == 1){ ?>
            <a class="cf tt-id" href="javascript:void(0)">
                <?php }else{ ?>
                <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'changeUsername','?'=>array('isRedirect'=>true))); ?>">
                    <?php } ?>
                    <span class="text"><?php echo __('Tên đăng nhập'); ?></span>
                    <span class="dataT"><?php echo $user['User']['username']; ?></span>
                </a>
        </li>
        <li>
            <div class="pRel">
                <?php if($email != '' && !$user['User']['email_verified']){ ?>
                    <a class="cf tt-lb tt-xacthuc" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addEmail','?'=>array('isRedirect'=>true))); ?>">
                        <span class="text"><?php echo __('Email đăng nhập'); ?></span>
                        <span class="dataT"><?php echo $email; ?></span>
                    </a>
                    <a class="xacthuc" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'verifyPinCodeEmail','?'=>array('isRedirect'=>true))); ?>"><?php echo __("Xác thực email") ;?></a>
                <?php }else{ ?>
                    <a class="cf tt-lb <?php echo $class_email_verify; ?>" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addEmail','?'=>array('isRedirect'=>true))); ?>">
                        <span class="text"><?php echo __('Email đăng nhập'); ?></span>
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
            <a class="cf tt-lb" href="javascript:void(0)" onclick="javascript:alert('Please enter and verify your email before creating a password')">
                <?php }else{ ?>
                <a class="cf tt-lb" href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'addPassword','?'=>array('isRedirect'=>true))); ?>">
                    <?php } ?>
                    <span class="text"><?php echo __('Mật khẩu'); ?></span>
                    <span class="dataT"><?php echo $user['User']['password'] !=null?'******':''; ?></span>
                </a>
        </li>
    </ul>
</div>