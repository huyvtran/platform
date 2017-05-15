<?php
    if(isset($this->request->data['Profile']['peopleId'])){
        $peopleId = $this->request->data['Profile']['peopleId'];
    }elseif(isset($profile['Profile']['peopleId']) && $profile['Profile']['peopleId'] != null ){
        $peopleId = $profile['Profile']['peopleId'];
    }else{
        $peopleId = '';
    }
    if(isset($this->request->data['Profile']['peopleId_date_get'])){
        $peopleId_date_get = $this->request->data['Profile']['peopleId_date_get'];
    }elseif(isset($profile['Profile']['peopleId_date_get']) && $profile['Profile']['peopleId_date_get'] != null ){
        $peopleId_date_get = $profile['Profile']['peopleId_date_get'];
    }else{
        $peopleId_date_get = '';
    }
    if(isset($this->request->data['Profile']['peopleId_place_get'])){
            $peopleId_place_get = $this->request->data['Profile']['peopleId_place_get'];
        }elseif(isset($profile['Profile']['peopleId_place_get']) && $profile['Profile']['peopleId_place_get'] != null ){
            $peopleId_place_get = $profile['Profile']['peopleId_place_get'];
        }else{
            $peopleId_place_get = '';
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
            <p class="rs tt-text"><?php echo __("Số CMND"); ?></p>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('peopleId', array(
                        'id' => 'infoIDCard',
                        'type'=>'number',
                        'label' => false,
                        'div' => false,
                        'maxlength'=>13,
                        'minlength'=>9,
                        'placeholder'=>'9 đến 13 số',
                        'errorMessage' => false,
                        'onkeypress'=>'return isNumberKey(event)',
                        'value' => $peopleId
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs tt-text"><?php echo __("Ngày cấp"); ?></p>
            <div class="tt-row">
                <input name="peopleId_date_get" id="infoCDay"  type="date" value="<?php echo $peopleId_date_get!= ''?date('Y-m-d',strtotime($peopleId_date_get)):''?>">
                <span class="icon-down"></span>
            </div>
            <p class="rs tt-text"><?php echo __("Nơi cấp"); ?></p>
            <div class="tt-row">
                <select name="peopleId_place_get">
                    <option value="<?php echo  $peopleId_place_get != ''? $peopleId_place_get:null ?>" selected><?php echo  $peopleId_place_get != ''? $peopleId_place_get:'--Tỉnh/TP--' ?></option>
                    <?php foreach($province as $place){ ?>
                        <option value="<?php echo $place ?>"><?php  echo $place ;?></option>
                    <?php } ?>
                </select>
                <span class="icon-down"></span>
            </div>
            <button  class="ttBtn-red"><?php echo __("Lưu"); ?></button>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
//    document.querySelector('#infoCDay').onchange = function() {
//        if (this.valueAsDate > new Date) {
//            alert("Ngày tháng nhập không hợp lệ")
//            $('#infoCDay').val('').focus();
//        }
//    };
</script>