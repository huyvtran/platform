<section id="wrapper">
    <article class="flogin">
        <?php echo $this->Session->flash("error_dashboardv2"); ?>
        <?php
            echo $this->Form->create('User', array());
        ?>
            <h4 class="rs">Quên mật khẩu</h4>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('password', array(
                        'id' => 'password',
                        'placeholder' => __('Mật khẩu mới ( 6-15 ký tự )'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <div class="tt-row tt-rowtext">
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
                <span class="icon-clear" >x</span>
            </div>
            <button class="fbutton btn-dnhap"><span>Hoàn thành</span></button>
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
