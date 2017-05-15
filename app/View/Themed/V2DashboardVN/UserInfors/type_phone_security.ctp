<?php
if(isset($this->request->data['Profile']['phone'])){
    $phone = $this->request->data['Profile']['phone'];
}else{
    $phone = '';
}
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <div class="box-ttBtn box-ttForm cf">
        <?php
        echo $this->Form->create('Profile', array());
        ?>
        <p class="rs tt-text"><?php echo __("SĐT di động") ;?></p>
        <div class="tt-row">
            <select class="code-phone" name="isoCode">
                <option value="<?php echo $pre_phone; ?>" selected>+<?php echo $pre_phone;?> (<?php echo $isoCode; ?>)</option>
                <?php foreach($CodeArea as $key => $value){ ?>
                    <option value="<?php echo $value; ?>"> +<?php echo $value.' ('. $key . ')'; ?>  </option>
                <?php } ?>
            </select>
            <?php
            echo $this->Form->input('phone', array(
                'id' => 'infoPhone',
                'class'=>'input-phone',
                'label' => false,
                'div' => false,
                'errorMessage' => false,
                'type' =>'number'
            ));
            ?>
            <span class="icon-clear">x</span>
        </div>
        <button  class="ttBtn-red"><?php echo __("Xác thực SĐT"); ?></button>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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
    $(function() {
        $('.tt-row').on('keydown', '#infoPhone', function(e){
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    })
</script>