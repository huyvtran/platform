<?php
$appkey = isset($this->request->query['app']) ? array('app' => $this->request->query['app']) : '';
?>
<section id="wrapper">
	<div class="content-wrap">
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
			</ul>
		</article>
		<article class="function">
			<ul>
				<!--nocache-->
				<li class="payment">
					<?php
					echo $this->Html->link(__('Mua xu'), "javascript:MobAppSDKexecute('mobBuyCoin', {})");
					?>
				</li>
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
			</ul>
		</article>
	</div>

</section>
<?php
echo $this->element('dump');
?>
