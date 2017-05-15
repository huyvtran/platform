<?php
$phone = '';
if(isset($this->request->query['phone']) && $this->request->query['phone'] != null ){
    $count_phone = strlen($this->request->query['phone']);
    $phone = substr($this->request->query['phone'],0,3).'*****'. substr($this->request->query['phone'],$count_phone -3,$count_phone);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        Hệ thống sẽ gửi mã xác thực vào SĐT <b><?php echo $phone; ?></b>.
    </p>
    <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
        <?php
        echo $this->Form->create('Profile', array());
        ?>
        <?php
        echo $this->Form->input('phone', array(
            'type'  =>'hidden',
            'value' => $this->request->query['phone']
        ));
        ?>
        <button type="submit" class="ttBtn-red">Gửi mã xác thực</button>

        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array('isRedirect'=>true))); ?>" class="ttBtn ttBtn-gray">Hủy</a>
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