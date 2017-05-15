<div id="sb-site" class="m-container">
	<div class="box-facebook n-border n-bg cf">
		<div class="ico"></div>
		<div class="short-desc">
			<a href="#">
				<b><?php echo __("Nối tài khoản với Facebook"); ?></b>
                <span>
                    <?php echo __("Bạn đang sử dụng chế độ đăng nhập nhanh. Tài khoản này có nguy cơ bị mất khi bạn cài lại game hay reset thiết bị. Hãy kết nối ngay với Facebook để bảo vệ tài khoản"); ?>
                </span>
			</a>
		</div>
		<div class="btn-face">
			<a href="javascript:MobAppSDKexecute('mobFacebookForUpdate', {})" class="has-coin"><?php echo __("Nối tài khoản với Facebook"); ?>
                <?php  if($currentGame['language_default'] == 'vie'){ ?>
                    <span class="fs-coin fspr">10</span>
                <?php } ?>
            </a>
		</div>
        <div class="message msg-error info-err"><?php echo $this->Session->flash('error_fb'); ?></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		center_content();
		$(window).resize(function() {
			center_content();
		});
		function center_content(){
			var wh = $(window).height(),
				h_content = $('.box-facebook').height();
			$('.box-facebook').css('marginTop', (wh - h_content - 40) / 2);
		}
	});
</script>