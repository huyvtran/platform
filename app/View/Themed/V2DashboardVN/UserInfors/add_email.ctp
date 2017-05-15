<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __('Hãy nhập địa chỉ email thường dùng của bạn. Bạn sẽ được yêu cầu xác thực địa chỉ email này.'); ?>
    </p>
    <div class="box-ttBtn box-ttForm box-ttBtnFull cf">
        <?php
            echo $this->Form->create('User', array());
        ?>
            <div class="tt-row">
<!--                <input name="email" id="email"  type="text" value="" placeholder="Nhập email">-->
                <?php
                    echo $this->Form->input('email', array(
                        'id' => 'email',
                        'placeholder' => 'Email',
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false
                    ));
                ?>
                <?php echo $this->Session->flash('error_dashboardv2');?>
                <span class="icon-clear">x</span>
            </div>
            <?php
                echo $this->Form->button(__('Xác thực email'), array(
                    'class' => 'ttBtn-red'
                ));
            ?>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>$ispop)) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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