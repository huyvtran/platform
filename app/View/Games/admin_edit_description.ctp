<?php
$this->extend('/Common/blank');
?>
<style type='text/css'>
	#GameAdminEditForm label {
		font-weight: bold;
	}
</style>
<?php echo $this->Session->flash('error'); ?>
<h4 class='page-header'>Edit game description</small></h4>
<h4 class='page-header'><?php echo $this->request->data['Game']['title'] ?> - <?php echo $this->request->data['Game']['os'] ?></h4>
<div class="games form">
<?php
	echo $this->Form->create('Game', array('type' => 'file'));

	echo $this->Form->hidden('id');
?>
<div class='row'>
<div class='span8'>
    <div class='span4'>
    <?php

        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'fbpage_id')
            echo $this->Form->input('fbpage_id',array('type'=>'text','label'=> 'Fbpage ID', 'style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('fbpage_id',array('type'=>'text','label'=> 'Fbpage ID'));


        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'fbpage_url')
            echo $this->Form->input('Game.data.fbpage_url',array('label'=> 'Fbpage Url', 'style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('Game.data.fbpage_url',array('label'=> 'Fbpage Url'));


        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'group_fb_id')
            echo $this->Form->input('Game.data.group_fb_id',array('type'=>'text','label'=> 'Group Facebook ID','style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('Game.data.group_fb_id',array('type'=>'text','label'=> 'Group Facebook ID'));

        echo $this->Form->input('Game.g_ver',array('label'=> 'Game Version'));
        echo $this->Form->input('Game.support_devices');

        echo '<label> Short description(<span class="count_char">0</span>/100)</label>';
        echo $this->Form->input('Game.data.short_description',array('type'=>'textarea','label'=>false))
    ?>
        <br/><br/>
        <p><label>Thời gian update Game</label></p>
            <?php
            echo $this->Form->input('Game.data.game_update_hour',array(
                'placeholder' => 'Time',
                'class'=>'timepicker form-control',
                'label'=>''
            ));
            echo $this->Form->input('Game.data.game_update_date',array(
                'placeholder' => 'Date',
                'class'=>'datepicker form-control',
                'label'=>''
            ));
            ?>

    </div>
    <div class='span3'>
    <?php

        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'support_skype')
            echo $this->Form->input('Game.data.support_skype',array('style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('Game.data.support_skype');

        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'support_email')
            echo $this->Form->input('support_email',array('label'=> 'Support Email','style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('support_email',array('label'=> 'Support Email'));

        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'appstore_link')
            echo $this->Form->input('appstore_link', array('label' => 'Appstore or Google Play link','style'=>'background-color: rgba(208, 92, 180, 0.48)'));
        else
            echo $this->Form->input('appstore_link', array('label' => 'Appstore or Google Play link'));

        echo $this->Form->input('jailbreak_link', array('label' => 'Apk, Ipa or Jailbreak Link'));

        $time_zone = array(
            'Pacific/Honolulu'      => 'Pacific/Honolulu (UTC-10)',
            'America/Juneau'        => 'America/Juneau (UTC-9)',
            'America/Los_Angeles'   => 'America/Los_Angeles (UTC-8)',
            'America/Phoenix'       => 'America/Phoenix (UTC-7)',
            'America/Chicago'       => 'America/Chicago (UTC-6)',
            'America/New_York'      => 'America/New_York (UTC-5)',
            'America/Manaus'        => 'America/Manaus (UTC-4)',
            'America/Fortaleza'     => 'America/Fortaleza (UTC-3)',
            'America/Sao_Paulo'     => 'America/Sao_Paulo (UTC-2)',
            'Etc/GMT-1'             => 'Etc/GMT-1 (GMT-1)',
            'Europe/London'         => 'Europe/London (UTC+0)',
            'Europe/Berlin'         => 'Europe/Berlin (UTC+1)',
            'Europe/Bucharest'      => 'Europe/Bucharest (UTC+2)',
            'Europe/Moscow'         => 'Europe/Moscow (UTC+3)',
            'Australia/Victoria'    =>'Australia/Victoria (UTC+4)',
            'Asia/Dushanbe'         => 'Asia/Dushanbe (UTC+5)',
            'Asia/Dhaka'            => 'Asia/Dhaka (UTC+6)',
            'Asia/Ho_Chi_Minh'      => 'Asia/Ho_Chi_Minh (GMT+7)',
            'Asia/Shanghai'         => 'Asia/Shanghai (GMT+8)',
            'Asia/Hong_Kong'        => 'Asia/Hong_Kong (UTC+8)',
            'Asia/Tokyo'            => 'Asia/Tokyo (UTC+9)',
            'Asia/Magadan'          => 'Asia/Magadan (UTC+10)',
            'Asia/Srednekolymsk'    => 'Asia/Srednekolymsk (UTC+11)',
            'Asia/Anadyr'           => 'Asia/Anadyr (UTC+12)'
        );
        echo $this->Form->input('Game.data.time_zone', array(
            'label' => 'Time zone',
            'empty' => "Empty",
            'options' => $time_zone,
        ));
        echo "<br/>";

        echo $this->Form->input('Genre', array(
            'multiple' => 'checkbox', 'options' => $genres,
            'label' => array('class' => 'control-label', 'text' => 'Thể loại',
            'div' => array('class' => 'control-group genres'))
        ));

        echo "<h5 class='page-header'>Publishing</h5>";
        echo $this->Form->input('status', array('label' => 'Show on stats'));
        echo $this->Form->input('Game.data.is_close', array('type'=>'checkbox','label' => 'Close this game'));
    ?>
    </div>

    <div class='span8'>
        <hr/>
        <?php
        if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'description'){
            echo "<strong style='background-color:rgba(208, 92, 180, 0.48)'>Mô Tả Game :</strong>";
            echo $this->Post->display('Game.description',array('style'=>'background-color:rgba(208, 92, 180, 0.48)'));
        }else{
            echo "<strong>Mô Tả Game :</strong>";
            echo $this->Post->display('Game.description');
        }
        ?>
    </div>
</div>
</div>

<div class='form-actions'>
    <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-primary')); ?>
</div>

<?php echo $this->Form->end() ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.published_game').hide();
        var published = $('#GamePublishedDate').val();
        if(published != ''){
            $('.published_game').show();
        }
        $('.datepicker').each(function(i, e) {
            var bindElement = $(e).data('bind');
            var now_date = new Date();
            var $input = $(e).pickadate({
                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyy-mm-dd',
                max: now_date.getDate() + 7
            });
            var picker = $input.pickadate('picker');
        });
        $('.timepicker').each(function(i, e) {
            var bindElement = $(e).data('bind');
            var $input = $(e).pickatime({
                format: 'HH:i',
                formatSubmit: 'HH:i'
            });
            var picker = $input.pickatime('picker');
        });
        // limit 100 char in short description
        $('#GameDataShortDescription').on('input',function(e){
            var short_description = $('#GameDataShortDescription').val();
            if(short_description.length >= 100){
                $('#GameDataShortDescription').val(short_description.substring(0,99));
            }
            count = $('#GameDataShortDescription').val();
            $('.count_char').text(count.length);
        });

        $(".show-config-box").click(function() {
            var boxconfig = $(this).next();
            if (boxconfig.is(':visible')) {
                boxconfig.hide();
            } else {
                boxconfig.show();
            }
            return false;
        });
    })
</script>
