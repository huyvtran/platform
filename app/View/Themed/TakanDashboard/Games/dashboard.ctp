<?php
$appkey = isset($this->request->query['appkey']) ? array('appkey' => $this->request->query['appkey']) : '';
?>
<section id="wrapper">
	<div class="content-wrap">
		<article class="content">

			<ul class="news-list" >
				<!--nocache-->
				<?php if (!$this->Session->read('Auth.User.email') && $this->Nav->showFunction('hide_update_account', $game['Game'])): ?>
					<li class="account">
						<a href="javascript:MobAppSDKexecute('mobOpenModal', {url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'updateGuestIndex'), true) ?>', title: '<?php echo __('Kết nối Facebook') ?>'})">
							<?php echo __('Upgrade account NOW!') ?><span class="ico-warrning"></span>
						</a>
					</li>
				<?php endif; ?>

				<li class="readmore">
					<?php
					echo $this->Html->link(__('Xem thêm'),
						array('controller' => 'notifications', 'action' => 'index',
							'?' => array(
								'user_id' => $this->Session->read('Auth.User.id') # this query use to avoid caching
							)
						)
					);
					?>
				</li>

				<!--/nocache-->
			</ul>
		</article>

		<article class="invite">
			<ul>
				<li class="inviteFb">
					<a href="<?php echo $this->Html->url(array('controller' => 'referrers', 'action' => 'index'), true) ?>">
						Mời bạn
						<span class="ico-email"></span><span class="ico-fb"></span></a>
				</li>
			</ul>
		</article>

		<article class="game-nav">
			<ul>
				<li class="news">
					<?php
					echo $this->Html->link(
						__('Tin tức & Sự Kiện'), array('controller' => 'categories', 'action' => 'index', 'news+events')
					);
					?>
				</li>
				<li class="home">
					<a href="javascript:MobAppSDKexecute('mobOpenBrowser', {url : 'http://<?php echo $website['url'] ?>/home'})">
						<?php echo __('Trang chủ') ?>
					</a>
				</li>
				<li class="facebook">
					<a href="javascript:MobAppSDKexecute('mobOpenFanPage', {pageid:<?php echo $game['Game']['fbpage_id'] ?>})">
						<?php echo __("Fanpage") ?>
					</a>
				</li>

				<?php
				
				$MobileDetect = new Mobile_Detect();
				$javascript = '';
				if ($MobileDetect->isiOS()) {
					$javascript = "javascript:MobAppSDKexecute('mobOpenBrowser', {url: 'fb://profile/722059147860077/'})";
				} elseif ($MobileDetect->isAndroidOS()) {
					$javascript = "javascript:MobAppSDKexecute('mobOpenGroup', {groupid: '722059147860077'})";
				}
				?>
				<li class="chat">
					<a href="<?php echo $javascript ?>"><?php echo __('Thảo luận chung') ?></a>
				</li>

				<!--<li class="group">-->
				<?php
//						echo $this->Html->link(
//								__('Hội nhóm'), array('controller' => 'clans', 'action' => 'index')
//						);
				?>
				<!--</li>-->
			</ul>
		</article>
		<article class="function">
			<ul>
<!--nocache-->
<?php
$gameVersion = $this->request->header('mobgame_app_version');
if ($this->Nav->showFunction('hide_payment', $game['Game'])) {
?>				
				<li class="payment">
					<?php
					echo $this->Html->link(__('Mua xu'), "javascript:MobAppSDKexecute('mobBuyCoin', {})");
					?>
				</li>
<?php
}
?>
<!--/nocache-->
				<li class="help">
					<?php
					echo $this->Html->link(__('Hướng dẫn'), array('controller' => 'categories', 'action' => 'dashboard'));
					?>
				</li>
				<li class="report">
					<?php
					echo $this->Html->link(__('Báo lỗi'), array('controller' => 'problems', 'action' => 'report'));
					?>
				</li>
				<li class="contact">
					<a href="javascript:MobAppSDKexecute('mobOpenContact', {support_email: '<?php echo $game['Game']['support_email'] ?>'})">
						<?php echo __('Liên hệ') ?>
					</a>
				</li>
				<!--nocache-->
				<?php
					if ($this->Nav->showFunction('hide_giftcode', $game['Game'])) {
				?>						
                 <li class="giftcode">
					<?php echo $this->Html->link(__('Giftcode'), array('controller' => 'giftcodes', 'action' => 'view')) ?>
				</li>
				<?php
				}
				?>
				<!--/nocache-->

				<!--nocache-->
				<?php
				// if (in_array($this->Session->read('Auth.User.email'), array('thinhnq@hotmail.com', 'khunglongbattu@yahoo.com', 'meotimdihia@gmail.com'))) {
					?>
					<li class="mpoints">
						<?php echo $this->Html->link(__('Đổi thưởng'), array('controller' => 'referrers', 'action' => 'mobpointToGiftcode')) ?>
					</li>
					<?php
				// }
				?>
				<!--/nocache-->
			</ul>
		</article>
		<article class="info">
			<ul>
				<li class="infoMe">
					<?php
					echo $this->Html->link(
						__('Cá nhân'), array('controller' => 'users', 'action' => 'view')
					);
					?>
				</li>
				<?php
				if (!empty($user['User']['facebook_uid'])){
				?>
				<!-- 					 	<li class="inviteFb">
											<a href="#">Mời bạn<span class="ico-fb"></span></a>
										</li> -->
				<?php
				}
				?>
			</ul>
		</article>
	</div>

	<!--nocache-->
	<div class="lb-overlay">
		<div class="pop">
			<p class="rs">Mời bạn bè cùng chơi game ngay!</p>
			<a href="javascript:void(0)" class="btnShare"><i class="fb-ico"></i> Đăng lên Facebook</a>
			<?php if ($MobileDetect->isAndroidOS()) : ?>
				<!--<a href="javascript:void(0)" class="btnShareGPlus"><i class="gg-ico"></i> Post to Google+</a>-->
			<?php endif; ?>
			<a href="javascript:void(0)" class="lb-close btnclose">Không, cảm ơn!</a>
		</div>
	</div>		
	<!--/nocache-->

</section>

<!--nocache-->
<script type="text/javascript">
//		var shouldPopup = <?php // echo ($shouldPopup) ? 'true' : 'false'; ?>;
	var shouldPopup = false;

	$(document).ready(function() {
		if (shouldPopup) {
			$('#wrapper').find('.lb-overlay').addClass('showShare');
		}
	});

	$('.btnclose').on('click', function() {
		$('#wrapper').find('.showShare').removeClass('showShare');
	});

	$('.btnShare').on('click', function() {
		var callbackUrl = '\/oauthv2\/afterFacebookShare.json';
		<?php if ($MobileDetect->isiOS()) : ?>
			callbackUrl = '<?php echo $this->Html->url(array('controller' => 'oauthv2', 'action' => 'afterFacebookShare', 'ext' => 'json')); ?>';
		<?php endif; ?>

		javascript:MobAppSDKexecute('mobFacebookShare', {
			'link': 'http://lienminhlol.net',
//				'link': 'http://google.com',
			'name': 'Liên Minh LOL',
			'caption': 'Liên Minh LOL',
			'description': 'Gia nhập Liên minh huyền thoại LOL trên smartphone',
			'image': '<?php echo $this->Html->url('/img/LOL-300.png', true); ?>',
			'clientState': {},
			'callbackUrl': callbackUrl
		});

		$('#wrapper').find('.showShare').removeClass('showShare');
	});

	$('.btnShareGPlus').on('click', function() {
		var callbackUrl = '\/oauthv2\/afterGPlusShare.json';
		<?php if ($MobileDetect->isiOS()) : ?>
			callbackUrl = '<?php echo $this->Html->url(array('controller' => 'oauthv2', 'action' => 'afterGPlusShare', 'ext' => 'json')); ?>';
		<?php endif; ?>

		javascript:MobAppSDKexecute('mobGPlusShare', {
			'title': 'Gia nhập Liên minh huyền thoại LOL trên smartphone',
			'link': 'http://lienminhlol.net',
			'callbackUrl': callbackUrl
		});

		$('#wrapper').find('.showShare').removeClass('showShare');
	});

</script>
<!--/nocache-->
<?php
echo $this->element('dump');
?>
