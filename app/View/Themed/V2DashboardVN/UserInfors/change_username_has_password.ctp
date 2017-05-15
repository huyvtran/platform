<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Mật khẩu"); ?>
    </p>
    <div class="box-ttBtn box-ttForm cf">
        <?php
            echo $this->Form->create('User', array());
        ?>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('password', array(
                        'id' => 'password',
                        'placeholder' => __("Mật khẩu"),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs tt-text"><?php echo __("Tên đăng nhập mới") ;?></p>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('username', array(
                        'id' => 'username',
                        'placeholder' => __("Tên đăng nhập mới"),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>

            <button  class="ttBtn-red"><?php echo __("Hoàn thành") ;?></button>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array('isRedirect'=>true))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy") ; ?></a>
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