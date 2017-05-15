<?php
	function check_like($count, $limit) {
		if ($count >= $limit) {
			$class = 'dat';
		} else {
			$class = 'chuadat';
		}
		return $class;
	}
	if ($currentGame['data']['limit_like'] != '') $limit_like = array_map('trim', explode("\n", $currentGame['data']['limit_like']));
	$like = $invite = $share = array();
	if (!empty($prize)) {
		foreach ($prize as $key => $value) {
			if ($value['LogReceviePrizeFb']['type'] == 'like') {
				$like[] = $value['LogReceviePrizeFb']['number'];
			}
			if ($value['LogReceviePrizeFb']['type'] == 'share') {
				$share[] = $value['LogReceviePrizeFb']['number'];
			}
			if ($value['LogReceviePrizeFb']['type'] == 'invite') {
				$invite[] = $value['LogReceviePrizeFb']['number'];
			}
		}
	}
?>
<div class="gift_sdk">
	<a href="javascript:MobAppSDKexecute('mobCloseWindow', {})" class="gift_close">X</a>
	<div class="wrap">
		<ul class="gift_nav cf">
			<li class="active"><a href="#tab1" >quà like</a><span class='warning'></span></li>
			<li><a href="#tab2">quà share</a> <span class="warning"></span></li>
			<li class="last"><a href="#tab3">quà invite</a> <span class="warning"></span></li>
		</ul>
		<div class="gift_content">
			<div id="tab1" class="tab" style="display:none">
				<ol>
					<?php
                    if(!isset($error)) {
                        if (isset($limit_like)) {
                            for ($i = 0; $i < count($limit_like); $i++) {
                                if (check_like($count_like, $limit_like[$i]) == 'dat') {
                                    $href = Router::url(array('controller' => 'Users', 'action' => 'reward', 'like', $limit_like[$i]));
                                } else {
                                    $href = 'javascript:void(0)';
                                }
                                if (!in_array($limit_like[$i], $like)) {
                                    ?>
                                    <li>
                                        <span class="like"></span>
										<span
                                            class="text"><?php echo $data_like[$limit_like[$i]]; ?>
                                        </span>
                                        <a href="<?php echo $href; ?>"
                                           class="quest_status <?php echo check_like($count_like, $limit_like[$i]) ?>"></a>
                                    </li>
                                <?php
                                }
                            }
                        }
                    }else{
                    ?>
                        <li>
                            <span class="text"><?php echo $error; ?></span>
                        </li>
                    <?php
                    }
					?>
				</ol>
				<div class="gift_bottom">
					<div class="fb_act"><a href="javascript:MobAppSDKexecute('mobOpenFanPage', {'pageid' : <?php echo $currentGame['fbpage_id']?>, 'callbackUrl' : '<?php echo Router::url(array('controller'=>'Users','action'=>'gift_facebook'))?>', 'isSocial' : true})"><img src="http://a.smobgame.com/plf/uncommon/dashboard_v2/images/fb_like.png" class="img_fb" height="25" alt=""><?php echo $count_like . __(' people like this.');?></a></div>
                    <a class="<?php echo $currentGame['alias'];?> gbtn-gr" href="#"></a>
				</div>
			</div>
			<div id="tab2" class="tab" style="display:none">
				<?php if (!empty($share_art)) {
					$url = 	$currentWebsite['url'] . '/' . $category['Category']['slug'] . '/' . $share_art['Article']['slug'];
				?>
					<div class="ads_img" style="background:url('<?php echo $share_art['AvatarShare']['data']['0']['aws']['ObjectURL']?>')"></div>
						<div class="gift_bottom"><a href="javascript:MobAppSDKexecute('mobActionShareFB', {'url' : 'http://<?php echo $url;?>', 'content' : '<?php echo $share_art['Article']['summary']?>', 'callbackUrl' : '<?php echo "http://a.smobgame.com".Router::url(array('controller'=>'Users','action'=>'reward', 'share', $share_art['Article']['id']))?>', 'isSocial' : true})" class="btn_share"><?php echo __('Share để nhận quà');?></a></div>
				<?php } else {
					debug($share_art);
					echo "<div class='gift_bottom'>" .__('Không có thông tin để share') . "</div>";
				}?>
			</div>
			<div id="tab3" class="tab" style="display:none">
				<ol>
					<?php
					if (!empty($data_invite)) {
					foreach ($data_invite as $key => $value) {
						if ($invite_friend_success >= $key) {
					?>
						<li><span class="qty"><?php echo $key?></span> <span class="text"><?php echo $value;?></span>
                            <a href="<?php echo Router::url(array('controller'=>'Users','action'=>'reward', 'invite', $key));?>" class="btn_recive org">Nhận</a>
                        </li>
					<?php
					} else {
						if (!in_array($key, $invite)) {
							?>
							<li><span class="qty"><?php echo $key ?></span> <span
									class="text"><?php echo $value; ?></span>
								<a href="javascript:MobAppSDKexecute('mobChooseFriend',
                        {'title' : '<?php echo $value; ?>',
                        'message' : 'Test message',
                        'callbackUrl' : 'http://a.smobgame.com/plf/users/add_invite_friend', 'isSocial' : true})" class="btn_recive org">Mời</a>
							</li>
							<?php
						}}}}
					$keys = array_keys($data_invite);
					$per  = ($invite_friend_success/end($keys)) * 100;
					?>
				</ol>
				<div class="gift_bottom">
					<i style="font-size:12px"><?php echo __('Mời càng nhiều, thưởng càng lớn');?></i>
					<div class="process_bar" data-value="<?php echo $per?>%"><div class="process"></div> <div class="process_status">Số người đã mời (<span id=""><?php echo $invite_friend_success;?></span>/<?php echo end($keys)?>)</div></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var process = $('.process_bar').data('value');
		if (process == ''){
			$('.process').css('width','0');
		} else {
			$('.process').css('width', process);
		}
		$(function(){
			$('.gift_nav li').first().addClass('active');
			$('.gift_content .tab').first().show();

			$('.gift_nav li').children('a').on('click',function(){
				if(!$(this).closest('li').hasClass('active')) {
					$('.gift_nav li').removeClass('active');
					$(this).closest('li').addClass('active');

					$('.gift_content .tab').hide();
					var $selected_tab = $(this).attr("href");
					$($selected_tab).show();
				}
				return false;
			})
		});
	});
</script>