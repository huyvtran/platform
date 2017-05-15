<section id="wrapper">
    <article class="flogin">
        <?php
        echo $this->Session->flash('error_dashboardv2');
        echo $this->Form->create('User', array(
            'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false)
        ));
        ?>
            <div class="tt-row tt-rowtext">
                <?php
                echo $this->Form->input('email', array('placeholder' => __('Email'), 'required' => 'required', 'type' => 'email', 'autocapitalize' => 'off'));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <div class="captcha">
                <img src="<?php echo $this->Html->url('/captcha/captcha/view/' . uniqid(), array('id' => 'img-capcha')); ?>" alt="" class="img-capcha" id="img-capcha">
                <a href="javascript:void(0);"><img src="http://a.smobgame.com/plf/uncommon/dashboard_v2/images/rf.png" id="captcha-reload" alt=""></a>
            </div>
            <div class="tt-row tt-rowtext">
                <?php echo $this->Form->input('ingamecode', array('placeholder' => 'Captcha', 'required' => 'required','label'=>false, 'class' => 'input-control', 'autocapitalize' => 'off')); ?>
                <span class="icon-clear" >x</span>
            </div>
            <button class="fbutton btn-dnhap"><span><?php echo __("Tiếp tục") ; ?></span></button>
            <?php echo $this->Form->end(); ?>
    </article>
</section>
<script type='text/javascript'>
    $(function(){
        $("body").removeClass("info");
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
        $('#captcha-reload').click(function(){
            var date = new Date();
            $("#img-capcha").attr('src',
                '<?php echo $this->Html->url(array("plugin" => "captcha", "controller" => "captcha", "action" => "view"));?>/' + date.getTime());
        })
    })
</script>