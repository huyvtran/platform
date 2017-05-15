<?php
if(isset($this->request->data['Profile']['answer1'])){
    $answer1 = $this->request->data['Profile']['answer1'];
}else{
    $answer1 = '';
}if(isset($this->request->data['Profile']['answer2'])){
    $answer2 = $this->request->data['Profile']['answer2'];
}else{
    $answer2 = '';
}
?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <div class="box-ttBtn box-ttForm cf">
        <?php
            echo $this->Form->create('Profile', array());
        ?>
            <p class="rs tt-text"><?php echo __("Câu hỏi bí mật 1");?></p>
            <div class="tt-row">
                <select name="question1">
                    <option disabled value="<?php echo  isset($profile['Profile']['question1'])&& $profile['Profile']['question1']!= null?$profile['Profile']['question1']:null ?>" selected><?php echo  isset($profile['Profile']['question1']) && $profile['Profile']['question1']!= null?$listQuestion1[$profile['Profile']['question1']]:'--Câu hỏi bí mật--' ?></option>
                    <?php foreach($listQuestion1 as $key => $value){ ?>
                        <option value="<?php echo $key ?>"><?php  echo $value ;?></option>
                    <?php } ?>
                </select>
                <span class="icon-down"></span>
            </div>
            <p class="rs tt-text"><?php echo __("Trả lời");?></p>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('answer1', array(
                        'id' => 'infoQ1',
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'value' => isset($profile['Profile']['answer1'])?$profile['Profile']['answer1']: $answer1
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>

            <p class="rs tt-text"><?php echo __("Câu hỏi bí mật 2");?></p>
            <div class="tt-row">
                <select name="question2">
                    <option disabled value="<?php echo  isset($profile['Profile']['question2'])&& $profile['Profile']['question2']!= null?$profile['Profile']['question2']:null ?>" selected><?php echo  isset($profile['Profile']['question2']) && $profile['Profile']['question2']!= null?$listQuestion2[$profile['Profile']['question2']]:'--Câu hỏi bí mật--' ?></option>
                    <?php foreach($listQuestion2 as $key => $value){ ?>
                        <option value="<?php echo $key ?>"><?php  echo $value ;?></option>
                    <?php } ?>
                </select>
                <span class="icon-down"></span>
            </div>
            <p class="rs tt-text"><?php echo __("Trả lời");?></p>
            <div class="tt-row">
                <?php
                echo $this->Form->input('answer2', array(
                    'id' => 'infoQ2',
                    'label' => false,
                    'div' => false,
                    'errorMessage' => false,
                    'value' => isset($profile['Profile']['answer2'])?$profile['Profile']['answer2']: $answer2
                ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <button  class="ttBtn-red"><?php echo __("Lưu");?></button>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserSecurity','?'=>array('isRedirect'=>true))) ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy");?></a>
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
</script>