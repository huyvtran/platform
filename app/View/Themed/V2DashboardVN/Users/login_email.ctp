<?php
if($currentGame['language_default'] == 'vie'){
    $placehoder_email  =   __('Email /SĐT /Tên đăng nhập');
}else{
    $placehoder_email  =   __('Email/Tên đăng nhập');
}
?>
<div class="game-bug-report">
    <section id="wrapper">
        <article class="flogin">
            <?php
                echo $this->Session->flash('flash', array('element' => 'error_dashboardv2'));
            ?>
            <?php
                echo $this->Form->create('User', array(
                    'name' => 'regisMail-form',
                    'autocomplete' => 'on',
                    'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false)));
            ?>
                <div class="tt-row tt-rowtext">
                    <?php
                        echo $this->Form->input('email', array(
                            'type' => 'text', 'id' => 'login', 'class' => 'reset full',
                            'placeholder' => $placehoder_email,
                            'required'=>false
                        ));
                    ?>
                    <span class="icon-clear" >x</span>
                </div>
                <div class="tt-row tt-rowtext">
                    <?php
                        echo $this->Form->input('password', array(
                            'type' => 'password', 'id' => 'password', 'class' => 'reset full',
                            'placeholder' => __('Mật khẩu (dài 6-15 ký tự)'),
                            'autocapitalize' => 'off', 'autocomplete' => 'off',
                            'required'=>false
                        ));
                    ?>
                    <span class="icon-clear" >x</span>
                </div>
            <?php
            echo $this->Form->button(__('Đăng nhập'), array(
                'type' => 'submit',
                'class' => 'fbutton btn-dnhap', 'name' => 'btn-Registry',
            ));
            ?>
            <?php echo $this->Form->end(); ?>
            <?php
            if($currentGame['language_default'] == 'vie'){
                echo $this->Html->link(__('Quên mật khẩu?'), array('controller' => 'users', 'action' => 'type_reset_password','?'=>array('back'=>true)), array('class' => 'f-forgotpass'));
            }else{
                echo $this->Html->link(__('Quên mật khẩu?'), array('controller' => 'users', 'action' => 'reset_password_mobile','?'=>array('back'=>true)), array('class' => 'f-forgotpass'));
            }
            ?>
            <?php
            if($currentGame['language_default'] == 'vie'){
                echo $this->Html->link('<button class="fbutton btn-dki" name="btn-signup">'. __('Đăng ký') . '</button>',
                    '/users/register_mobile', array('escape' => false));
            }else{
                echo $this->Html->link('<button class="fbutton btn-dki" name="btn-signup">'. __('Đăng ký') . '</button>',
                    '/users/register_mobile', array('escape' => false));
            }
            ?>
            <?php
            ?>
            <?php
            if(isset($currentGame['data']['display_logo_older']) && $currentGame['data']['display_logo_older'] == 1 ){
                if(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 0){
                    ?>
                    <p class="textAc rs" style="text-align: center;"><img src="http://cdn.smobgame.com/newfolder/limit/ev.png" width="60" height="86" class="ghdt"></p>
                <?php }elseif(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 12){ ?>
                    <p class="textAc rs" style="text-align: center;"><img src="http://cdn.smobgame.com/newfolder/limit/12t.png" width="60" height="86" class="ghdt"></p>
                <?php }elseif(isset($currentGame['data']['logo_older']) && $currentGame['data']['logo_older'] == 18){ ?>
                    <p class="textAc rs" style="text-align: center;"><img src="http://cdn.smobgame.com/newfolder/limit/18t.png" width="60" height="86" class="ghdt"></p>
                <?php } ?>
                <h4 style="text-align: center">Chơi quá 180 phút một ngày sẽ ảnh hưởng xấu đến sức khỏe </h4>
            <?php } ?>
        </article>
    </section>
</div>
<script>
    $(function() {
        $("body").removeClass("info");
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
