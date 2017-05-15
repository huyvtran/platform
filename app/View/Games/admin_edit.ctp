<?php
$this->extend('/Common/blank');
?>
<style type='text/css'>
	#GameAdminEditForm label {
		font-weight: bold;
	}
	.config-box {
		display: none;
	}
</style>
<h3 class='page-header'>Admin Edit Game</h3>
<div class="games form">
	<?php echo $this->Form->create('Game', array(
		'type' => 'file'));
	?>
	<div class='row'>
		<div class='span3'>
			<?php
			echo $this->Form->input('id');
			echo $this->Form->input('title');
			echo $this->Form->input('slug', array('label' => array('text' => 'Slug (*)')));
			echo $this->Form->input('short_words', array('label' => array('text' => 'Short Words')));
			echo $this->Form->input('alias', array('label' => array('text' => 'Alias')));
			echo $this->Form->input('os', array(
				'type' => 'select', 'options' => array(
					'' => 'Not set',
					'ios' => 'iOS',
					'android' => 'Android',
					'pc' => 'PC',
					'wp' => 'Windows Phone'
				),
				'label' => array('text' => 'OS')
			));
			echo $this->Form->input('app', array('readonly' => 'readonly', 'label' => 'App Key (*)'));
			echo $this->Form->input('secret_key', array('readonly' => 'readonly', 'label' => 'Secret Key (*)'));
			echo $this->Form->input('screen', array(
				'options' => array(
					'horizontal' => 'horizontal',
					'vertical' => 'vertical'
				),
				'empty' => '--Choose vertical or horizontal--',
				'label' => 'Screen'
			));
			?>
		</div>

		<div class='span3'>
			<?php
			echo $this->Form->input('app_paypalid');

            if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'fb_appid'){
                echo $this->Form->input('fb_appid', array('label' => 'Fb Appid (*)','style'=>'background-color: rgba(208, 92, 180, 0.48)'));
                echo $this->Form->input('fb_appsecret',array('style'=>'background-color: rgba(208, 92, 180, 0.48)'));
            }else{
                echo $this->Form->input('fb_appid', array('label' => 'Fb Appid (*)'));
                echo $this->Form->input('fb_appsecret');
            }
			?>
		</div>
		<div class='span3'>
			<?php
			echo $this->Form->input('app_theme');

            if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'dashboard_gaid')
                echo $this->Form->input('dashboard_gaid',array('style'=>'background-color: rgba(208, 92, 180, 0.48)'));
            else
			    echo $this->Form->input('dashboard_gaid');

            if(isset($this->request->query['error_field']) && $this->request->query['error_field'] == 'gcm_key')
			    echo $this->Form->input('gcm_key',array('style'=>'background-color: rgba(208, 92, 180, 0.48)'));
            else
                echo $this->Form->input('gcm_key');

			$L10n = new L10n();
			foreach (Game::$usedLanguages as $key => $value) {
				$info = $L10n->catalog($value);
				if ($info == false) {
					// 'zh_cn' => 'Simplified Chinese',
					// 'zh_tw' => 'Traditional Chinese',
					$info = $L10n->catalog(str_replace('_', '-', $value));
				}
				$optionLangs[$value] = $info['language'];
			}
			echo $this->Form->input('language_default', array(
				'options' => $optionLangs,
				'empty' => '--Choose language default--'
			));
			?>
			<div class="input">
				<?php
				echo $this->Form->input('website_id', array(
					'empty' => true, 'type' => 'select', 'multiple' => false
				));
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

	$(function() {
		$(".show-config-box").click(function() {
			var boxconfig = $(this).next();
			if (boxconfig.is(':visible')) {
				boxconfig.hide();
			} else {
				boxconfig.show();
			}
			return false;
		});
            $('.datepicker').each(function(i, e) {
                var bindElement = $(e).data('bind');
                var now_date = new Date();
                var $input = $(e).pickadate({
                    format: 'yyyy-mm-dd',
                    formatSubmit: 'yyyy-mm-dd',
                    min: now_date,
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
        });
</script>
