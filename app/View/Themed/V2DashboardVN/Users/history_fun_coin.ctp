<?php
if (in_array(date('m',time()), array('1','2','3','4','5','6','12'))) {
	$text = "Từ 09/12/" . date('Y', strtotime('- 1 year', time())). " - 08/06/" . date('Y', time());
} else {
	$text = "Từ 09/06/" . date('Y', time()). " - 08/12/" . date('Y', strtotime('+ 1 year', time()));
}
?>
<section id="wrapper">
	<div class="box-coinUser has-date">
		<span class="f-coin">FunCoin tích lũy: </span>
		<span class="f-numCoin"><?php echo $sum + $funpoin_payment;?></span>
		<?php if($this->request->header('mobgame-sdk-version') != false && $this->request->header('mobgame-sdk-version') >='2.3.0' ){ ?>
			<a href="<?php echo $this->Html->url(array( 'action' => 'mission')) ?>" class="nhiemvu"><?php echo __('Nhiệm vụ');?></a>
		<?php } ?>
		<p class="date"><em><?php echo $text?></em></p>
	</div>
	<div class="list-task list-lichsu">
		<?php
		if (isset($funpoints)) {
			$icon = '';
			foreach ($funpoints as $funpoint) {
				if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_LIKE_FACEBOOK) {
					$icon = 'like';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_INVITE_FRIEND) {
					$icon = 'invite';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_REGISTER) {
					$icon = 'ketnoi';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_SHARE_FACEBOOK) {
					$icon = 'share';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_UPDATE_INFO || $funpoint['Funpoint']['type'] == Funpoint::TYPE_UPDATE_INFO_LOGIN) {
					$icon = 'taikhoan';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_LOGIN_DAILY  ||
					$funpoint['Funpoint']['type'] == Funpoint::TYPE_FIRST_LOGIN  ||
					$funpoint['Funpoint']['type'] == Funpoint::TYPE_3_DAYS_LOGIN ||
					$funpoint['Funpoint']['type'] == Funpoint::TYPE_7_DAYS_LOGIN ||
					$funpoint['Funpoint']['type'] == Funpoint::TYPE_30_DAYS_LOGIN)
				{
					$icon = 'login';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_PAYMENT || $funpoint['Funpoint']['type'] == Funpoint::TYPE_PAYMENT_COMPENSE) {
					$icon = 'card';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_RESET_CSKH) {
					$icon = 'reset';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_BIRTHDAY) {
					$icon = 'birthday';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_PLAY_GAME_ADS) {
					$icon = 'dl';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_INSTALL_GAME_ADS) {
					$icon = 'play';
				} elseif ($funpoint['Funpoint']['type'] == Funpoint::TYPE_UPDATE_INFO_PERSONAL ||
					$funpoint['Funpoint']['type'] == Funpoint::TYPE_UPDATE_INFO_SECURITY)
				{
					$icon = 'hoanthien';
				} else {
					$icon = 'hoanthien';
				}
				?>
				<div class="box-task task-<?php echo $icon;?>">
					<span class="task-ico spr"></span>
					<div class="task-inner">
						<h3 class="rs task-name">
							<?php
							if ($funpoint['Funpoint']['title'] == '' && $funpoint['Funpoint']['type'] == Funpoint::TYPE_PAYMENT) {
								$title = "Nạp tiền ". $funpoint['Funpoint']['points'] ."000 ₫ vào game " . $funpoint['Game']['title'];
								echo __("$title");
							} elseif ($funpoint['Funpoint']['title'] == '' && $funpoint['Funpoint']['type'] == Funpoint::TYPE_RESET_CSKH) {
								echo __("FunCoin hết hạn trong kỳ tính hạng KHTT mới");
							} else if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_LIKE_FACEBOOK) {
								echo __('Like fanpage game ') . $funpoint['Funpoint']['title'];
							} else if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_SHARE_FACEBOOK) {
								echo __('Chia sẻ game ') . $funpoint['Funpoint']['title'];
							} else if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_PLAY_GAME_ADS) {
								echo __('Thưởng đăng nhập game ') . $funpoint['Funpoint']['title'];
							} else if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_INSTALL_GAME_ADS) {
								echo __('Thưởng cài đặt game ') . $funpoint['Funpoint']['title'];
							} else if ($funpoint['Funpoint']['type'] == Funpoint::TYPE_INVITE_FRIEND) {
								echo __('Thưởng mời bạn bè');
							} else {
								echo $funpoint['Funpoint']['title'];
							}
							?>
						</h3>
						<p class="rs task-text">Ngày <?php echo date('d/m/Y', strtotime($funpoint['Funpoint']['created']));?></p>
					</div>
					<a href="javascript:void(0)" class="task-go <?php if ($funpoint['Funpoint']['points'] < 0) echo 'minus'?>"><?php echo ($funpoint['Funpoint']['points'] > 0) ? '+' : '';?><?php echo $funpoint['Funpoint']['points'];?></a>
				</div>
			<?php }}?>
		<input type="hidden" id="load">
		<?php if ($total > 20) {?>
			<a href="javascript:void(0)" class="list-more"><?php echo __('Xem thêm');?></a>
		<?php }?>
	</div>
</section>
<div id="result"></div>
<script type="text/javascript">
	$(document).ready(function(){
		var url = '<?php echo Router::Url(array('controller' => 'Users', 'action' => 'historyFunPoint')); ?>';
		var number_record = 20;
		var start         = 20;
		var text_default  = $('.list-more').text();
		var loading       = '<?php echo __('Loading ...')?>';
		var like          = '<?php echo Funpoint::TYPE_LIKE_FACEBOOK;?>';
		var invite        = '<?php echo Funpoint::TYPE_INVITE_FRIEND;?>';
		var share         = '<?php echo Funpoint::TYPE_SHARE_FACEBOOK;?>';
		var register      = '<?php echo Funpoint::TYPE_REGISTER;?>';
		var update        = '<?php echo Funpoint::TYPE_UPDATE_INFO;?>';
		var update_login  = '<?php echo Funpoint::TYPE_UPDATE_INFO_LOGIN;?>';
		var login_daily   = '<?php echo Funpoint::TYPE_LOGIN_DAILY;?>';
		var first_login   = '<?php echo Funpoint::TYPE_FIRST_LOGIN;?>';
		var day_3         = '<?php echo Funpoint::TYPE_3_DAYS_LOGIN;?>';
		var day_7         = '<?php echo Funpoint::TYPE_7_DAYS_LOGIN;?>';
		var day_30        = '<?php echo Funpoint::TYPE_30_DAYS_LOGIN;?>';
		var payment       = '<?php echo Funpoint::TYPE_PAYMENT;?>';
		var payment_con   = '<?php echo Funpoint::TYPE_PAYMENT_COMPENSE;?>';
		var birthday      = '<?php echo Funpoint::TYPE_BIRTHDAY;?>';
		var reset_cskh    = '<?php echo Funpoint::TYPE_RESET_CSKH;?>';
		var play_game_ads = '<?php echo Funpoint::TYPE_PLAY_GAME_ADS;?>';
		var install_game_ads = '<?php echo Funpoint::TYPE_INSTALL_GAME_ADS;?>';
		var update_personal = '<?php echo Funpoint::TYPE_UPDATE_INFO_PERSONAL?>';
		var update_security = '<?php echo Funpoint::TYPE_UPDATE_INFO_SECURITY?>';
		var total         = <?php echo $total;?>;
		var page          = Math.floor(total/start);
		var du            = total%start;
		var counter       = 0;
		if (total <= start) {
			$('.list-more').remove();
		}
		$('.list-more').click(function(){
			counter += 1;
			if (!$(this).hasClass('clicked')) {
				$(this).addClass('clicked').text(loading);
				$.ajax({
					type: "POST",
					url: url,
					dataType: 'json',
					data: {start:start},
					success : function(result) {
						if (result) {
							var html  = '';
							var icon  = '';
							var title = '';
							$.each(result, function(key, value) {
								if (value['Funpoint']['type'] == like) {
									icon = 'like';
								} else if (value['Funpoint']['type'] == invite) {
									icon = 'invite';
								} else if (value['Funpoint']['type'] == share) {
									icon = 'share';
								} else if (value['Funpoint']['type'] == register) {
									icon = 'ketnoi';
								} else if (value['Funpoint']['type'] == update || value['Funpoint']['type'] == update_login) {
									icon = 'taikhoan';
								} else if (value['Funpoint']['type'] == login_daily || value['Funpoint']['type'] == first_login ||
									value['Funpoint']['type'] == day_3 || value['Funpoint']['type'] == day_7 || value['Funpoint']['type'] == day_30) {
									icon = 'login';
								} else if (value['Funpoint']['type'] == payment || value['Funpoint']['type'] == payment_con) {
									icon = 'card';
								} else if (value['Funpoint']['type'] == birthday) {
									icon = 'birthday';
								} else if (value['Funpoint']['type'] == reset_cskh) {
									icon = 'reset';
								} else if (value['Funpoint']['type'] == play_game_ads) {
									icon = 'dl';
								} else if (value['Funpoint']['type'] == install_game_ads) {
									icon = 'play';
								} else if (value['Funpoint']['type'] == update_personal || value['Funpoint']['type'] == update_security) {
									icon = 'hoanthien';
								}
								html += '<div class="box-task task-' + icon + '">';
								html += '<span class="task-ico spr"></span>';
								html += '<div class="task-inner"><h3 class="rs task-name">';
								if (value['Funpoint']['type'] == payment) {
									title = '<?php echo __('Nạp tiền ')?>' + value['Funpoint']['points'] + '<?php echo __('000 ₫ vào game ')?>' + value['Game']['title'];
								} else if (value['Funpoint']['type'] == reset_cskh) {
									title = '<?php echo __("FunCoin hết hạn trong kỳ tính hạng KHTT mới");?>';
								} else if (value['Funpoint']['type'] == like) {
									title = '<?php echo __('Like fanpage game ');?>' + value['Funpoint']['title'];
								} else if (value['Funpoint']['type'] == share) {
									title = '<?php echo __('Share game ');?>' + value['Funpoint']['title'];
								} else if (value['Funpoint']['type'] == play_game_ads) {
									title = '<?php echo __('Thưởng đăng nhập game ');?>' + value['Funpoint']['title'];
								} else if (value['Funpoint']['type'] == install_game_ads) {
									title = '<?php echo __('Thưởng cài đặt game ');?>' + value['Funpoint']['title'];
								}else {
									title = value['Funpoint']['title'];
								}
								html += title;
								html += '</h3><p class="rs task-text">';
								html += 'Ngày ' + value[0]['created_day'];
								html += '</p></div>';
								html += '<a href="javascript:void(0)" class="task-go">';
								if (value['Funpoint']['points'] > 0) {
									html += '+';
								}
								html += value['Funpoint']['points'];
								html += '</a></div>';
							});
							$('#load').before(html);
							$('.list-more').removeClass('clicked').text(text_default);
							start += number_record;
						} else {
							$('.list-more').remove();
						}
					}
				})
			}
			if (page == 1) {
				$('.list-more').remove();
			} else {
				if (du != 0) {
					if (page == counter) {
						$('.list-more').remove();
					}
				} else {
					if (page == (counter+1)) {
						$('.list-more').remove();
					}
				}
			}
		})
	})
</script>