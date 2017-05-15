<?php
    $count          =   strlen($profile['Profile']['email_contact']);
    $email_contact  =   substr($profile['Profile']['email_contact'],0,3).'****@***'.substr($profile['Profile']['email_contact'],$count-5,$count);
    $ispop = array();
    if(isset($this->request->query['ispop'])){
        $ispop = array('ispop'=>true);
    }
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Để đổi email bảo mật, hệ thống sẽ gửi email xác thực vào");?> <strong><?php echo $email_contact; ?></strong>
    </p>
    <div class="box-ttBtn box-ttBtnFull cf">
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendChangeEmailSecurity','?'=>array_merge(array('sent'=>true,'isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-red"><?php echo __("Xác thực email"); ?></a>
    <?php  if($currentGame['language_default'] != 'vie'){ ?>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
    <?php }else{ ?>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
    <?php } ?>
    </div>
</div>