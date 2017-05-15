<?php
    $email = $this->request->query['email'];
    $count  =   strlen($email);
    $email  =   substr($email,0,3).'****@***'.substr($email,$count-5,$count);
    $ispop = array();
    if(isset($this->request->query['ispop'])){
        $ispop = array('ispop'=>true);
    }
?>
<?php echo $this->Session->flash('send_success');?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
<p class="rs tt-text">
    <?php echo __("Hãy nhập mã xác thực gửi vào email <strong>%s</strong>. Nếu chưa nhận được email xin vui lòng chờ ít phút hoặc kiểm tra mục Thư rác/Spam/Junk.",$email) ; ?>
</p>
<div class="box-ttBtn box-ttBtnFull box-ttForm cf">
    <?php
        echo $this->Form->create('User', array());
    ?>
        <div class="tt-row tt-rowtext">
            <?php
                echo $this->Form->input('codePin', array(
                    'id' => 'codePin',
                    'placeholder' => __('Nhập mã PIN'),
                    'label' => false,
                    'div' => false,
                    'errorMessage' => false,
                    'type'  => 'password'
                ));
            ?>
            <span class="icon-clear">x</span>
        </div>
        <p class="rs tt-textLast"><?php echo __("Không nhận được PIN?"); ?>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendAgain','?'=>array_merge(array('redirect'=>'confirmChangeEmailLogin','type'=>'cfChangeEmailLogin','email'=>$this->request->query['email']),$ispop))) ?>"><?php echo __("Gửi lại") ;?></a></p>
        <button  class="ttBtn-red"><?php echo __("Đổi email"); ?></button>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'typeEmailLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-green"><?php echo __("Sử dụng email khác"); ?></a>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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