<?php
$phone = '';
if(isset($profile['Profile']['phone']) && $profile['Profile']['phone'] != null ){
    $count_phone = strlen($profile['Profile']['phone']);
    if($this->request->query['isoCode'] == '84'){
        $phone = substr($profile['Profile']['phone'],1,3).'*****'. substr($profile['Profile']['phone'],$count_phone -3,$count_phone);
    }else{
        $phone = substr($profile['Profile']['phone'],0,3).'*****'. substr($profile['Profile']['phone'],$count_phone -3,$count_phone);
    }
    $ispop = array();
    if(isset($this->request->query['ispop'])){
        $ispop = array('ispop'=>true);
    }
}
?>
<?php echo $this->Session->flash('send_success');?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        Nhập mã xác thực đã gửi vào SĐT <b><?php echo '(+'.$this->request->query['isoCode'].') '. $phone; ?></b>
    </p>
    <div class="box-ttBtn box-ttForm  cf">
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
            Không nhận được mã?
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendSmsAgain','?'=>array_merge(array('redirect'=>'enterCodeVerifyPhone','phone'=>$profile['Profile']['phone'],'isoCode'=>$this->request->query['isoCode']),$ispop))) ?>" class="linkstyle">Gửi lại</a>

            <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
                <button  class="ttBtn-red">Xong</button>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>" class="ttBtn ttBtn-gray">Hủy</a>
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