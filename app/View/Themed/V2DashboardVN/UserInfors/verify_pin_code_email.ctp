<?php
    $email = $user['User']['email'];
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
        <?php echo __("Hãy nhập mã xác thực đã gửi vào mail <strong>%s</strong> của bạn. Nếu chưa nhận được email xin vui lòng chờ ít phút hoặc kiểm tra mục Thư rác/Spam/Junk",$email);?>
    </p>
    <div class="box-ttBtn box-ttForm cf">
        <?php
            echo $this->Form->create('User', array());
        ?>
            <div class="tt-row tt-rowtext">
<!--                <input name="codext" id="codext"  type="text" value="" placeholder="Nhập mã xác thực">-->
                <?php
                    echo $this->Form->input('pinCode', array(
                        'id' => 'codext',
                        'placeholder' => __('Nhập mã xác thực'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs"><?php echo __("Không nhận được email xác thực?"); ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendCodeAgainEmail','?'=>array_merge(array('redirect'=>'verifyPinCodeEmail'),$ispop))) ?>"><?php echo __("Gửi lại"); ?></a></p>
            <?php
                echo $this->Form->button(__('Hoàn thành'), array(
                    'class' => 'ttBtn-done'
                ));
            echo $this->Form->end();
            ?>


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