<?php
    $email_login = $user['User']['email'];
    $email_contact = $user['Profile']['email_contact'];
    $phone_login = $user['User']['phone'];
    $phone_security = $user['Profile']['phone'];
    $phone = '';
    if(!empty($phone_security)){
        $phone = $phone_security;
    }elseif(!empty($phone_login)){
        $phone = $phone_login;
    }
?>
<section id="wrapper">
    <article class="flogin">
        <?php echo $this->Session->flash("error_dashboardv2"); ?>
        <?php
            echo $this->Form->create('User', array());
        ?>
            <h4 class="rs">Quên mật khẩu</h4>
            <p class="f-logintext1 rs">Chúng tôi sẽ hỗ trợ bạn lấy lại mật khẩu dựa trên các thông tin đăng ký.</p>
            <div class="tt-rowtext">
                <?php if($email_contact != null){
                    $count  =   strlen($email_contact);
                    $email_contact  =   substr($email_contact,0,3).'****@***'.substr($email_contact,$count-5,$count);
                ?>
                    <input name="rthongtin" id="email_profile" type="radio" value="email_profile" >
                    <label for="email_profile"><span class="f-Pico"></span>Sử dụng email bảo vệ tài khoản <br> <strong>( <?php echo $email_contact; ?> )</strong></label>
                <?php }else{ ?>
                    <input name="rthongtin" id="email_profile" type="radio" value="email_profile" disabled >
                    <label for="email_profile"><span class="f-Pico"></span>Sử dụng email bảo vệ tài khoản</strong></label>
                <?php } ?>
            </div>
            <div class="tt-rowtext">
                <?php if($email_login != null && strpos($email_login, '@haitacmobi.com') === false){
                    $count  =   strlen($email_login);
                    $email  =   substr($email_login,0,3).'****@***'.substr($email_login,$count-5,$count);
                    ?>
                    <input name="rthongtin" id="email_login" type="radio" value="email_login" >
                    <label for="email_login"><span class="f-Pico"></span>Sử dụng email đăng nhập <br> <strong>( <?php echo $email; ?> )</strong></label>
                <?php }else{ ?>
                    <input name="rthongtin" id="email_login" type="radio" value="email_login" disabled >
                    <label for="email_login"><span class="f-Pico"></span>Sử dụng email đăng nhập</strong></label>
                <?php } ?>
            </div>
        <div class="tt-rowtext">
                <?php if($phone != ''){
                        $count_phone = strlen($phone);
                        $phone = substr($phone,0,3).'*****'. substr($phone,$count_phone -3,$count_phone);
                ?>
                    <input name="rthongtin" id="phone_profile" type="radio" value="phone_profile" >
                    <label for="phone_profile"><span class="f-Pico"></span>Sử dụng SĐT <br> <strong>( <?php echo $phone; ?> )</strong></label>
                <?php }else{ ?>
                    <input name="rthongtin" id="remail" type="radio" value="phone_profile" disabled >
                    <label for="remail"><span class="f-Pico"></span>Sử dụng SĐT</strong></label>
                <?php } ?>
        </div>
            <button class="fbutton btn-dnhap"><span>Tiếp tục</span></button>
        <?php
            echo $this->Form->end();
        ?>
    </article>
</section>
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