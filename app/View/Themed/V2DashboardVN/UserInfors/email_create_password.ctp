<?php
$email = $user['User']['email'];
$count  =   strlen($email);
$email  =   substr($email,0,3).'****@***'.substr($email,$count-5,$count);
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Đồng ý tạo mật khẩu cho tài khoản đăng nhập <strong>%s</strong>? Gửi email xác thực. Nếu chưa nhận được email xin vui lòng chờ ít phút hoặc kiểm tra mục Thư rác/Spam/Junk.",$email); ?>
    </p>
    <div class="box-ttBtn box-ttBtnFull cf">
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'emailCreatePassword','?'=>array_merge(array('sendto'=>true),$ispop))) ?>" class="ttBtn ttBtn-red"><?php echo __("Gửi email xác thực") ?></a>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy") ?></a>
    </div>
</div>