<?php
$phone = '';
if(isset($profile['Profile']['phone']) && $profile['Profile']['phone'] != null ){
    $count_phone = strlen($profile['Profile']['phone']);
    $phone = substr($profile['Profile']['phone'],0,3).'*****'. substr($profile['Profile']['phone'],$count_phone -3,$count_phone);
}
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        Trước khi tiếp tục, hệ thống sẽ gửi mã xác thực vào SĐT<b>
            <?php
                if($this->request->query['isoCode'] != '84') {
                    echo '(+' . $this->request->query['isoCode'] . ') ' . $phone;
                }else{
                    echo '(+' . $this->request->query['isoCode'] . ') ' . substr($phone,1,$count_phone);
                }
            ?>
        </b>.
    </p>
    <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
        <?php
        echo $this->Form->create('Profile', array());
        ?>
        <?php
        echo $this->Form->input('phone', array(
            'type'  =>'hidden',
            'value' => $profile['Profile']['phone']
        ));
        ?>
        <button type="submit" class="ttBtn-red">Gửi mã xác thực</button>

        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>" class="ttBtn ttBtn-gray">Hủy</a>
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