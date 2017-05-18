<?php
if (	$this->request->header('mobgame_appkey')
	||  !empty($currentGame['os'])
) {
	
	$MobileDetect = new Mobile_Detect();
?>
		<?php
		if (	@$currentGame['os'] == 'wp'
			|| 	(!empty($MobileDetect) && $MobileDetect->isWindowsPhoneOS())
		) {
			echo $this->Html->script('/js/jquery/jquery-1.11.2.min.js');
		} elseif (	@$currentGame['os'] == 'ios'
				||  $MobileDetect->isiOS()
		) {
			echo $this->Html->script('/js/zepto-1.0.min.js');
		} elseif (	@$currentGame['os'] == 'android'
				||  $MobileDetect->isAndroidOS()
		) {
			echo $this->Html->script('/js/zepto-1.0.min.js');
		} else {
			echo $this->Html->script('/js/jquery/jquery-1.11.2.min.js');
		}
}
?>