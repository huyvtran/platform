<?php
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Email bảo mật") ; ?>
    </p>
    <div class="box-ttBtn box-ttBtnFull cf">
        <?php
            echo $this->Form->create('Profile', array());
        ?>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('email_contact', array(
                        'id' => 'email_contact',
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'  =>'email'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <button  class="ttBtn ttBtn-red"><?php echo __("Xác thực email") ; ?></button>
        <?php  if($currentGame['language_default'] != 'vie'){ ?>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy") ; ?></a>
        <?php }else{ ?>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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