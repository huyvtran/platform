<!--nocache-->
<?php
if (	$this->request->header('app')
	||  !empty($currentGame['os'])
) {
	
	$MobileDetect = new Mobile_Detect();
?>
	<script type='text/javascript'>
		<?php
		if ( @$currentGame['os'] == 'wp'
			|| 	(!empty($MobileDetect) && $MobileDetect->isWindowsPhoneOS())
		) {
		?>
			var AppSDKexecute = function(command, params) {
				try {
					obj = {
						command: command,
						params: JSON.stringify(params)
					};
					var pass = JSON.stringify(obj);
					window.external.notify(pass);
				} catch (err) {
					console.log(err);
				}
			};
		<?php
		} elseif (	@$currentGame['os'] == 'ios'
				||  $MobileDetect->isiOS()
		) {
		?>
			var AppSDKexecute = function(command, params) {
                window.webkit.messageHandlers.{command}.postMessage({data: params, handler: command});
			}
		<?php
			if (!empty($commandIOS)) {
				echo $commandIOS;
			}
		} elseif (	@$currentGame['os'] == 'android'
				||  $MobileDetect->isAndroidOS()
		) {

		?>
		function AppSDKexecute(command, params){
			try{
				var infoText = JSON.stringify(params);
				JsHandler.AppSDKexecute(command,infoText);
			} catch(err) {
				console.log(err);
			}
		}

		<?php
		}
		?>
		if (typeof $ != "undefined") {
			$.ajaxSettings = $.extend($.ajaxSettings, {
				headers: {
					'app': '<?php echo $this->request->header('app') ?>',
					'token': '<?php echo $this->request->header('token') ?>'
				}
			})
		}

	</script>
<?php
}
?>
<!--/nocache-->