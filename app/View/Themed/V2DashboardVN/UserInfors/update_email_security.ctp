<?php
$email =  $this->Session->read('Auth.User.email');
if($email != null && strpos($email, '@haitacmobi.com') === false && strpos($email, '@facebook.com') === false &&
    $this->Session->read('Auth.User.facebook_uid') != null){
    $email_contact = $email;
}else{
    if(isset($this->request->data['Profile']['email_contact'])){
        $email_contact = $this->request->data['Profile']['email_contact'];
    }else{
        $email_contact = '';
    }
}
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Hãy nhập địa chỉ email thường dùng của bạn. Địa chỉ email này sẽ được sử dụng trong trường hợp chúng tôi cần liên hệ với bạn. Bạn sẽ được yêu cầu xác thực địa chỉ email") ;?>
    </p>
    <div class="box-ttBtn box-ttBtnFull cf">
        <?php
            echo $this->Form->create('Profile', array());
        ?>
            <div class="tt-row">
                <?php
                echo $this->Form->input('email_contact', array(
                    'id' => 'infoEmail',
                    'type'=>'email',
                    'placeholder'=> __("Email liên hệ"),
                    'label' => false,
                    'div' => false,
                    'errorMessage' => false,
                    'value' => isset($profile['Profile']['email_contact'])?$profile['Profile']['email_contact']: $email_contact
                ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <button  class="ttBtn-red"><?php echo __("Gửi email xác thực"); ?></button>
            <?php if($currentGame['language_default'] == 'vie'){ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
            <?php }else{ ?>
                <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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