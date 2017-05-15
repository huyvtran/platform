<div class="tt-notice">
    Email xác thực đã được gửi lại
</div>
<div class="box-thongtin">
    <p class="rs tt-error">Xác thực không thành công</p>
    <p class="rs tt-text">
        Hệ thống đã gửi mã xác thực vào SĐT <b>098****045</b>. Hãy nhập mã xác thực để hoàn tất:
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
            Không nhận được mã? <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendSmsAgain','?'=>array('redirect'=>'confirmChangePhoneFinish','phone'=>$this->request->query['phone']))) ?>" class="linkstyle">Gửi lại</a>

            <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
                <button  class="ttBtn-red">Xong</button>
                <a href="javascript:void(0)" class="ttBtn ttBtn-green">Dùng SĐT khác</a>
                <a href="javascript:void(0)" class="ttBtn ttBtn-gray">Hủy</a>
            </div>
        </form>
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