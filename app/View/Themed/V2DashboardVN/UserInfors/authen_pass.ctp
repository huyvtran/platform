<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Nhập mật khẩu") ; ?>
    </p>
    <div class="box-ttBtn box-ttForm  cf">
        <?php
            echo $this->Form->create('User', array());
        ?>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('password', array(
                        'id' => 'password',
                        'placeholder' => __('Mật khẩu'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <button  class="ttBtn ttBtn-red"><?php echo __('Tiếp tục') ?></button>
            <?php if(isset($this->request->query['person']) && $this->request->query['person'] == true ){ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __('Hủy') ?></a>
            <?php }elseif(isset($this->request->query['login']) && $this->request->query['login'] == true){ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __('Hủy') ?></a>
        <?php }else{
                if($currentGame['language_default'] != 'vie'){
                ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __('Hủy') ?></a>
                 <?php }else{ ?>
                        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __('Hủy') ?></a>
                 <?php } ?>
        <?php } ?>
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