<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __('Mật khẩu cũ') ?>
    </p>
    <div class="box-ttBtn box-ttBtnFull box-ttForm cf">
        <?php
            echo $this->Form->create('User', array());
        ?>

            <div class="tt-row">
                <?php
                    echo $this->Form->input('oldPassword', array(
                        'id' => 'oldPassword',
                        'placeholder' => __('Nhập mật khẩu cũ'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs tt-text">
                <?php echo __('Nhập mật khẩu mới') ?>
            </p>
            <div class="tt-row tt-rowtext">
                <?php
                echo $this->Form->input('password', array(
                    'id' => 'password',
                    'placeholder' => __('Mật khẩu mới'),
                    'label' => false,
                    'div' => false,
                    'errorMessage' => false,
                    'type'=>'password'
                ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <div class="tt-row">
                <?php
                echo $this->Form->input('temppassword', array(
                    'id' => 'repassword',
                    'placeholder' => __('Xác nhận mật khẩu'),
                    'label' => false,
                    'div' => false,
                    'errorMessage' => false,
                    'type'=>'password'
                ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <button  class="ttBtn-red"><?php echo __('Hoàn thành') ?></button>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __('Hủy') ?></a>
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