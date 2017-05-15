<section id="wrapper">
    <article class="flogin">
        <?php
            echo $this->Session->flash('error_dashboardv2');
        ?>
<!--        <p class="rs tt-error">Email không hợp lệ</p>-->
        <?php
            echo $this->Form->create('User', array(
                'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false))
            );
        ?>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('phone', array(
                        'id' => 'phone',
                        'placeholder' => 'SĐT di động. Vd: 098......'
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <p class="rs f-logintext">Bạn cần xác thực SĐT này ở bước tiếp theo</p>
            <button class="fbutton btn-dnhap"><span>Đăng ký</span></button>
        <?php
            echo $this->Form->end();
        ?>
        <a href="javascript:void(0)" class="f-line">Hoặc</a>
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register_mobile')) ?>" class="fbutton btn-dki"><span>Đăng ký bằng Email</span></a>
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