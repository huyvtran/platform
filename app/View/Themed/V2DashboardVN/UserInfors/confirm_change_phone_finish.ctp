<?php
$phone = '';
if(isset($this->request->query['phone']) && $this->request->query['phone'] != null ){
    $count_phone = strlen($this->request->query['phone']);
    $phone = substr($this->request->query['phone'],0,3).'*****'. substr($this->request->query['phone'],$count_phone -3,$count_phone);
}
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<?php echo $this->Session->flash('send_success');?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        Hệ thống đã gửi mã xác thực vào SĐT <b><?php echo '(+'.$this->request->query['isoCode'].') '. substr($phone,1,$count_phone); ?></b>. Hãy nhập mã xác thực để hoàn tất:
    </p>
    <div class="box-ttBtn box-ttBtnFull box-ttForm  cf">
        <?php
        echo $this->Form->create('Profile', array());
        ?>
        <div class="tt-row">
            <?php
            echo $this->Form->input('codePin', array(
                'id' => 'codePin',
                'placeholder' => __('Nhập mã xác thực'),
                'label' => false,
                'div' => false,
                'errorMessage' => false,
                'type'  =>'password'
            ));
            ?>
            <span class="icon-clear">x</span>
        </div>
        Không nhận được mã? <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendSmsAgain','?'=>array_merge(array('redirect'=>'confirmChangePhoneFinish','phone'=>$this->request->query['phone'],'isoCode'=>$this->request->query['isoCode']),$ispop))) ?>" class="linkstyle">Gửi lại</a>

        <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
            <button type="submit" class="ttBtn-red">Xong</button>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'typePhoneSecurity1','?'=>$ispop)); ?>" class="ttBtn ttBtn-green">Dùng SĐT khác</a>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>$ispop)); ?>" class="ttBtn ttBtn-gray">Hủy</a>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
    $(function() {
        $(".icon-clear").bind('touchstart click', function(e) {
            e.preventDefault();
            $(this).siblings('input').val('').focus();
            $(this).hide();
        });
        $('.tt-row input').on('blur', function(){
            $(this).siblings('.icon-clear').hide();
        }).on('focus', function(){
            if ($(this).val() !== '') {
                $(this).siblings('.icon-clear').show();
            }
        });
        $("form").on('keyup touchstart', 'input', clearIcon);

    });
    function clearIcon(event) {
        checkShowClearIcon(event.currentTarget);
    }
    function checkShowClearIcon(input) {
        if (input.value == '') {
            $(input).siblings('.icon-clear').hide();
        } else {
            $(input).siblings('.icon-clear').show();
        }
    }
</script>