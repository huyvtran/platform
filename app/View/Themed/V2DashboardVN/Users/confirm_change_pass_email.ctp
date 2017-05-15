<?php
    $count  =   strlen($this->request->query['email']);
    $email  =   substr($this->request->query['email'],0,3).'****@***'.substr($this->request->query['email'],$count-5,$count);
?>
<?php echo $this->Session->flash("send_success"); ?>
<section id="wrapper">
    <article class="flogin">
        <?php echo $this->Session->flash("error_dashboardv2"); ?>
        <?php
            echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false)));
        ?>
            <h4 class="rs">Quên mật khẩu</h4>
            <p class="f-logintext1 rs">Hệ thống đã gửi mã xác nhận vào <strong><?php echo $email; ?></strong>.Nhập mã để tiếp tục.</p>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('codePin', array(
                        'id' => 'idxnhan',
                        'placeholder' => 'Mã xác nhận',
                        'type'=>'password','label'=>false
                    ));
                ?>
                <span class="icon-clear" >x</span>
            </div>
            <button class="fbutton btn-dnhap"><span>Tiếp tục</span></button>
            <p class="rs f-logintext">Không nhận được mã?
                <a href="<?php echo $this->Html->url(array('controller'=>'Users','action' => 'sendEmailAgain','?'=>array('redirect'=>$this->request->action,'type'=>'ecode_change_pass','isRedirect'=>true,'g'=>$this->request->query['g'],'email'=>$this->request->query['email']))) ?>" class="red"> <u>Gửi lại</u>
                </a></p>
            <p class="f-logintext1 rs">Lưu ý : Vui lòng chờ giây lát hoặc kiểm tra mục Thư rác / Spam / Bulk</p>
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