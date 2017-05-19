<!--nocache-->
<?php
if (	$this->request->header('mobgame_appkey')
	||  !empty($currentGame['os'])
) {
	
	$MobileDetect = new Mobile_Detect();
?>
	<script type='text/javascript'>
		<?php
		if (	@$currentGame['os'] == 'wp'
			|| 	(!empty($MobileDetect) && $MobileDetect->isWindowsPhoneOS())
		) {
		?>
			var MobAppSDKexecute = function(command, params) {
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
			var MobAppSDKexecute = function(command, params) {
				var iframeCmd = document.createElement('iframe');
				iframeCmd.src = "iosapp://" + command + "/:sdkparams:/" + encodeURI(JSON.stringify(params));
				document.documentElement.appendChild(iframeCmd);
				iframeCmd.parentNode.removeChild(iframeCmd);
				iframeCmd=null;
			}
		<?php
			if (!empty($commandIOS)) {
				echo $commandIOS;
			}
		} elseif (	@$currentGame['os'] == 'android'
				||  $MobileDetect->isAndroidOS()
		) {

		?>
		function MobAppSDKexecute(command, params){
			try{
				var infoText = JSON.stringify(params);
				window.JsHandler.mobAppSDKexecute(command,infoText);
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
					'mobgame-appkey': '<?php echo $this->request->header('mobgame_appkey') ?>',
					'mobgame-token': '<?php echo $this->request->header('mobgame_token') ?>'
				}
			})
		}

	</script>

    <!-- share 1 link article to app other -->
    <?php if(!empty($category['Website']) &&
            !empty($article['Article']['title']) && $article['Article']['title'] != '' && !empty($this->request['pass'][0])) { ?>
        <script type="text/javascript">
            function getShareUrl() {
                var result_url = "<?php echo $category['Website']['url'] . DS . 'news' . DS . $this->request['pass'][0] . DS . $this->request['pass'][1]; ?>";
                var result_title = "<?php echo $article['Article']['title']; ?>";
                result_url = result_url.replace(/http:\/\//g,"");
                result_url = "http://" + result_url;
                var result = {url:encodeURI(result_url), title:result_title};

                return JSON.stringify(result);
            }
        </script>
    <?php } ?>
<?php
}
?>
<!--/nocache-->