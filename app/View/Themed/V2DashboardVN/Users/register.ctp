<section id="wrapper">
    <article class="flogin">
        <?php
//            echo $this->Session->flash('flash');
            echo $this->Session->flash('flash', array('element' => 'error_dashboardv2'));
        ?>
        <?php
            echo $this->Form->create('User', array(
                    'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false))
            );
        ?>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('email', array(
                        'id' => 'email', 'class' => 'reset full',
                        'placeholder' => 'Email',
                        'required'=>false
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('username', array(
                        'id' => 'username', 'class' => 'reset full',
                        'placeholder' => __('Tên đăng nhập dài 5-20 ký tự'), 'minlength' => 5, 'maxlength' => 20,
                        'required'=>false
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('password', array(
                        'type' => 'text',
                        'id' => 'password', 'class' => 'reset full',
                        'placeholder' => __('Mật khẩu (dài 6-15 ký tự)'), 'minlength' => 6, 'maxlength' => 20,
                        'autocapitalize' => 'off', 'autocomplete' => 'off',
                        'required'=>false
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
        <?php
            echo $this->Form->button(__('Đăng ký'), array(
                'class' => 'fbutton btn-dnhap', 'name' => 'btn-Registr',
            ));
        ?>
        <?php
            echo $this->Form->end();
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