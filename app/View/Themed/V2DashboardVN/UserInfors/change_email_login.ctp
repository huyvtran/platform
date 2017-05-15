<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Để thay đổi địa chỉ email đăng nhập của tài khoản FunID bạn sẽ nhận được một email xác nhận.") ; ?>
    </p>
    <p class="rs tt-text">
        <?php echo __("Lưu ý: Sau khi đổi địa chỉ email đăng nhập sang địa chỉ email mới, bạn sẽ sử dụng email mới này để đăng nhập trên tất cả các game cũ bạn đã chơi") ; ?>
    </p>
    <div class="box-ttBtn box-ttBtnFull cf">
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'changeEmailLogin','?'=>array_merge(array('sent'=>true,'isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-red"><?php echo __("Gửi email xác thực") ;?></a>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy") ;?></a>
    </div>
</div>