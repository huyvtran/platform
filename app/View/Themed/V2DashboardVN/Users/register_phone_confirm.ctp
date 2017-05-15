<?php
    $phone = '';
    if(isset($this->request->query['phone']) && $this->request->query['phone'] != '' ){
        $count_phone = strlen($this->request->query['phone']);
        $phone = substr($this->request->query['phone'],0,3).'*****'. substr($this->request->query['phone'],$count_phone -3,$count_phone);
    }
?>
<section id="wrapper">
    <?php
        echo $this->Session->flash('send_success');
    ?>
    <article class="flogin">
        <?php
            echo $this->Session->flash('error_dashboardv2');
        ?>
        <p class="f-logintext1 rs">Hệ thống đã gửi mã xác nhận vào SĐT <strong><?php echo $phone; ?></strong>. Hãy nhập mã vào ô dưới đây để hoàn tất.</p>
        <?php
            echo $this->Form->create('User', array(
                    'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false))
            );
        ?>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('codePin', array(
                        'id' => 'idxnhan',
                        'placeholder' => 'Mã xác nhận',
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <?php if($number_send <2){ ?>
                <p class="rs f-logintext">Không nhận được mã? <a href="<?php echo $this->Html->url(array('controller'=>'Users','action' => 'sendSmsAgain','?'=>array('type'=>'register','redirect'=>'register_phone_confirm','phone'=>$this->request->query['phone']))) ?>" class="red"> <u>Gửi lại </u>(<?php echo $number_send; ?>/2)</a></p>
            <?php }else{ ?>
                <p class="rs f-logintext">Không nhận được mã? <a href="javascript:void (0)" class="red"> <u>Gửi lại </u>(2/2)</a></p>
            <?php } ?>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('username', array(
                        'id' => 'username',
                        'placeholder' => 'Tên đăng nhập ( 5-20 ký tự )'
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('password', array(
                        'id' => 'password',
                        'placeholder' => 'Mật khẩu ( 6-15 ký tự )',
                        'type'=>'text'
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <button class="fbutton btn-dnhap"><span>Xong</span></button>
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