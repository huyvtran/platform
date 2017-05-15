<?php
    if(isset($this->request->data['infoBDay'] )){
        $infoBDay = $this->request->data['infoBDay'];
    }else{
        $infoBDay = '';
    }
if(isset($this->request->data['Profile']['fullname'] )){
        $fullname = $this->request->data['Profile']['fullname'];
    }else{
        $fullname = '';
    }
if(isset($this->request->data['Profile']['address'] )){
        $address = $this->request->data['Profile']['address'];
    }else{
        $address = '';
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
            <p class="rs tt-text"><?php echo __("Họ tên"); ?></p>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('fullname', array(
                        'id' => 'infoName',
                        'placeholder' => __("Họ tên"),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'value' => isset($profile['Profile']['fullname'])?$profile['Profile']['fullname']: $infoBDay

                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs tt-text"><?php echo __("Ngày sinh"); ?></p>
            <div class="tt-row">
                <input name="infoBDay" id="infoCDay"  type="date" value="<?php echo isset($profile['Profile']['birthday'])?date('Y-m-d',strtotime($profile['Profile']['birthday'])):$infoBDay?>">
                <span class="icon-down"></span>
            </div>
            <p class="rs tt-text"><?php echo __("Giới tính"); ?></p>
            <div class="tt-row">
                <select name="gender">
                    <option disabled value="<?php echo isset($profile['Profile']['gender']) && $profile['Profile']['gender'] != null?$profile['Profile']['gender']:0 ?>" selected><?php echo isset($profile['Profile']['gender']) && $profile['Profile']['gender'] != null?__($profile['Profile']['gender']):'--'.__("Giới tính").'--' ?></option>
                    <option value="<?php echo __("Nam");?>"><?php echo __("Nam");?></option>
                    <option value="<?php echo __("Nữ");?>"><?php echo __("Nữ");?></option>
                    <option value="<?php echo __("Không xác định");?>"><?php echo __("Không xác định");?></option>
                </select>
                <span class="icon-down"></span>
            </div>
            <p class="rs tt-text"><?php echo __("Địa chỉ"); ?></p>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('address', array(
                        'id' => 'infoHome',
                        'placeholder' => __("Địa chỉ"),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'value'=> isset($profile['Profile']['address'])?$profile['Profile']['address']: $address
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <?php if($currentGame['language_default'] == 'vie'){ ?>
                <p class="rs tt-text"><?php echo __("Tỉnh/TP"); ?></p>
                <div class="tt-row">
                    <select name="province">
                        <option disabled value="<?php echo  isset($profile['Profile']['province'])&& $profile['Profile']['province']!= null?$profile['Profile']['province']:0 ?>" selected><?php echo  isset($profile['Profile']['province']) && $profile['Profile']['province']!= null?$profile['Profile']['province']:'--Tỉnh/TP--' ?></option>
                        <?php foreach($province as $place){ ?>
                            <option value="<?php echo $place ?>"><?php  echo $place ;?></option>
                        <?php } ?>
                    </select>
                    <span class="icon-down"></span>
                </div>
        <?php }else{ ?>
                <p class="rs tt-text"><?php echo "Country"; ?></p>
                <div class="tt-row">
                    <select name="country">
                        <option disabled value="<?php echo  isset($profile['Profile']['country'])&& $profile['Profile']['country']!= null?$profile['Profile']['country']:0 ?>" selected><?php echo  isset($profile['Profile']['country']) && $profile['Profile']['country']!= null?$profile['Profile']['country']:'--Country--' ?></option>
                        <?php foreach($countries as $country){ ?>
                            <option value="<?php echo $country ?>"><?php  echo $country ;?></option>
                        <?php } ?>
                    </select>
                    <span class="icon-down"></span>
                </div>
        <?php } ?>
            <button  class="ttBtn-red"><?php echo __("Lưu"); ?></button>
        <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserPersonal','?'=>array_merge(array('isRedirect'=>true),$ispop))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
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
    document.querySelector('#infoCDay').onchange = function() {
        if (this.valueAsDate > new Date) {
            alert('<?php echo __("Ngày sinh không hợp lệ") ?>');
            $('#infoCDay').val('').focus();
        }
    };
</script>