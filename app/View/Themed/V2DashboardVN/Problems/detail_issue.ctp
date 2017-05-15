<div  class="m-container">
	<?php
	if (isset($this->request->named['id'])) {
		$id = $this->request->named['id'];
	} else {
		$id = 0;
	}
	if (isset($this->request->query['done'])) {
		$done = 1;
	} else {
		$done = 0;
	}
	$error = $this->Session->flash('error');
	if ($error != false && !empty($error)) {
		?>
		<div class="error_mess"><?php echo $error;?></div>
		<?php
	}
	if (!empty($data)) {
		?>
		<div class="box-ticket">
			<div class="box-lstTick box-lstTickI  t-tt3">
				<i class="t-ico"></i>
			<span class="t-lstTitle t-lstTitleI">
			<?php
			echo h($data[0]['Problem']['title']);
			?>
			</span>
            <span class="t-wrap">
	            <?php if($data[0]['Problem']['status'] == Problem::STATUS_RESOLVED) {?>
		            <span class="t-tt t-close"><?php echo __('Closed');?></span>
	            <?php } else {?>
		            <span class="t-tt"><?php echo __('Open');?></span>
	            <?php }?>
	            <span class="t-lstId">Ticket # <?php echo $data[0]['Problem']['id']?></span>
            </span>
			</div>
			<div class="box-ticksp">
				<?php
				if ($total > 5) {?>
					<a href="javascript:void(0)" class="tick-moreI"><?php echo __('Xem thêm trao đổi cũ');?></a>
				<?php }?>
				<input type="hidden" id="load">
				<?php
				$k = 0;
				foreach($data as $value) {
					?>
					<div class="box-tickTimeline">
						<?php if($value['ProblemDashboard']['type'] == Problem::USER_GUEST) {?>
							<i class="t-ico" style="background: url(<?php echo $avatar;?>) 50% 50% no-repeat"></i>
							<h4 class="rs t-TimeTitle"><?php echo __('Bạn');?></h4>
						<?php } else if ($value['ProblemDashboard']['type'] == Problem::USER_SUPPORTER) {
							if ($name == 'Funtap') {
								echo '<i class="t-ico" style="background: url(' . "http://a.smobgame.com/plf/uncommon/dashboard_v2/images/ava-f.png" . ') 50% 50% no-repeat"></i>';
							} else {
								echo '<i class="t-ico" style="background: url(' . "http://a.smobgame.com/plf/uncommon/dashboard_v2/images/ava-m.png" . ') 50% 50% no-repeat"></i>';
							}
							?>
							<h4 class="rs t-TimeTitle"><?php echo __("CSKH ") . $name;?></h4>
						<?php }?>
						<div class="rs t-TimeDes">
							<?php
							if ($k == 0 && $total <= 5) {
								echo __("Tên nhân vật : ") . $value['Problem']['character']. "<br>";
								echo __("Server: ") . $value['Problem']['server']. "<br>";
								if ($value['Problem']['card_type'] != '' && $value['Problem']['card_serial'] != '') {
									echo __("Loại thẻ: ") . ucfirst($value['Problem']['card_type']) . "<br>";
									echo __("Serial thẻ: ") . $value['Problem']['card_serial'] . "<br>";
								}
								if ($value['Problem']['phone'] != '') {
									echo __("SĐT liên hệ: ") . $value['Problem']['phone'] . "<br>";
								}
							}
							$body = str_replace("<br />", '', $value['ProblemDashboard']['body']);
							$body = str_replace("&nbsp;", '', $body);
							$body = str_replace("<p>&nbsp;</p>", '', $body);
							$body = str_replace("<p></p>", '', $body);
							$body = trim($body);
							if($value['ProblemDashboard']['type'] == Problem::USER_GUEST) {
								echo nl2br($body);
							} else if ($value['ProblemDashboard']['type'] == Problem::USER_SUPPORTER) {
								echo $body;
							}
							if (!empty($value['Attach'])) {
								echo '<div class="img_view">';
								foreach ($value['Attach'] as $v) {
									echo '<a href="http://cdn.smobgame.com/' . $v['name']. '">' . $this->Nav->image($v, 50, 50, array(
											'retina' => true,
											'empty' => array('u' => 'no_thumb.jpg')
										)) . '</a>';
								}
								echo '</div>';
							}
							$k++;
							?>
						</div>
				<span class="t-TimeT">
				<?php
				if (date('d', strtotime($value['ProblemDashboard']['created'])) == date('d', time())) {
					echo $this->Nav->display_time($value['ProblemDashboard']['created']);
				} else {
					echo date('d/m/Y, H:i A', strtotime($value['ProblemDashboard']['created']));
				}
				?>
				</span>
					</div>
				<?php }?>
				<?php if($data[0]['Problem']['status'] == Problem::STATUS_RESOLVED) {?>
					<div class="box-tickN">
						<a href="javascript:void(0)" class="btn-topen"><?php echo __('Mở lại lỗi');?></a>
					</div>
				<?php } else {?>
					<div class="box-tickN cf" id="ph">
						<a href="javascript:void(0)" class="btn-trep"><?php echo __('Phản hồi');?></a>
						<a href="javascript:void(0)" class="btn-tok" id="solved"><?php echo __('Đã giải quyết');?></a>
					</div>
					<div id="reply">
						<div class="box-tickTimeline box-tickTimelineRep">
							<i class="t-ico" style="background: url(<?php echo $avatar;?>) 50% 50% no-repeat"></i>
							<h4 class="rs t-TimeTitle"><?php echo __('Phản hồi của bạn');?></h4>
						</div>
						<?php
						echo $this->Form->create('ProblemDashboard', array(
							'class' => 'formTicketRep',
							'type' => 'file',
						));
						?>
						<div class="box-tickForm">
							<?php
							echo $this->Form->input('body', array('label' => false,'div' => false,'required' => false, 'id' => 'tick_desc', 'rows' => 8, 'cols' => 15));
							?>
						</div>
						<div id="img_preview"></div>
						<div class="box-tickBt cf">
							<?php if (!in_array($currentGame['id'], array('238', '230'))) {?>
							<?php if (($currentGame['os'] == 'ios' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '5'))
								|| ($currentGame['os'] == 'android' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '4'))) { ?>
                                <a href="javascript:MobAppSDKexecute('loadImageUpload', {'function' : 'getImageData'})"
                                   id="fileSelect" class="tick-btn tick-file"><?php echo __('Chọn file'); ?></a>
								<?php
							} else if (in_array($currentGame['id'], array('230'))) { ?>
                                <a href="javascript:MobAppSDKexecute('loadImageUpload', {'function' : 'getImageData'})"
                                   id="fileSelect" class="tick-btn tick-file"><?php echo __('Chọn file'); ?></a>
                            <?php }}
							echo $this->Form->input(__('Gửi'), array(
								'class' => 'tick-btn tick-sent',
								'type' => 'button',
								'name' => 'submit',
								'label' => false,
								'div' => false,
							));
							?>
							<button id="xoa" class="tick-btn tick-remove" type="button"><?php echo __('Xóa');?></button>
						</div>
						<?php echo $this->Form->end();?>
					</div>
				<?php }?>
			</div>
		</div>
	<?php }?>
</div>
<div id="bottom_scroll" style="position: absolute;bottom: 0"></div>
<?php echo $this->Html->script('/js/js_cskh/jquery.magnific-popup.js');?>
<script type="text/javascript">
	<?php if ($id != 0) {?>
	MobAppSDKexecute('mobGetIssue', {id: '<?php echo $id ?>'});
	<?php } else { ?>
	MobAppSDKexecute('mobGetError');
	<?php }?>
	var arr = new Array();
	function getImageData(base64, id) {
		var preview = document.getElementById("img_preview");
		preview.innerHTML = '';
		arr[id] = base64;
		for (var key in arr) {
			var img = document.createElement('img');
			var a   = document.createElement('a');
			var input = document.createElement('input');
			var div = document.createElement('div');
			a.setAttribute('href', "javascript:MobAppSDKexecute('deleteImageData', {'index' : '" + key + "'})");
			a.setAttribute('id', 'img' + key);
			a.setAttribute('class', 'img_delete');
			a.setAttribute('data_index', key);
			a.textContent = 'Xóa';
			input.setAttribute('type', 'hidden');
			input.setAttribute('id', 'anh');
			input.setAttribute('name', "data[ProblemDashboard][img]");
			input.setAttribute('value', '1');
			img.setAttribute('height', '50px');
			img.setAttribute('width', '50px');
			img.setAttribute('src', "data:image/png;base64," + arr[key]);
			div.setAttribute('id', 'image' + key);
			div.setAttribute('class', 'img_pre');
			div.appendChild(a);
			div.appendChild(img);
			preview.appendChild(div);
			preview.appendChild(input);
			document.getElementById("img" + key).addEventListener("click", function(){
				var index = this.getAttribute('data_index');
				delete arr[index];
				document.getElementById('image' + index).remove();
				this.remove();
			});
		}
	}
	$('.img_view').magnificPopup({
		delegate: 'a',
		type: 'image',
		key:'closeOnBgClick',
		gallery: {
			enabled: true
		}
	});
	$(document).ready(function(){
		$(window).load(function() {
			$("html, body").animate({ scrollTop: $(document).height() }, 1000);
		});
		var counter = 0;
		function loadimage()
		{
			counter++;
			var id  = '<?php echo $id?>';
			var issue_id = '<?php echo $issue_id?>';
			var done = '<?php echo $done?>';
			var url = '<?php echo Router::Url(array('controller' => 'Problems', 'action' => 'check_image')); ?>';
			if (id != 0) {
				var send_id = id;
				var type = 1;
			} else {
				send_id = issue_id;
				type = 2;
			}
			if (send_id != 0 && done != 1) {
				$.ajax({
					type: "POST",
					url: url,
					dataType: 'json',
					data: {id: send_id, type: type},
					success: function (result) {
						if (result.code == 1) {
							window.location.href = '<?php echo Router::Url(array('controller' => 'Problems', 'action' => 'detail_issue', $issue_id, '?' => array('done' => 1))); ?>';
						}
					}
				});
			}
			console.log("Counter is: " + counter);
			if ((counter == 5 || done == 1) && send_id != 0) {
				clearInterval(looper);
			}
		}
		var id = '<?php echo $id?>';
		if (id != 0) {
			var looper = setInterval(loadimage, 10000);
		}

		$('#xoa').click(function(){
			var body = $('#tick_desc').val();
			if (body == '' && $(".img_pre").length == 0) {
				$('#reply').hide();
				$('#ph').show();
				location.reload();
			} else {
				var status = confirm('<?php echo __("Bạn có chắc chắn muốn xóa không ?");?>');
				if (status == true) {
					location.reload();
				}
			}
		});
		$('#reply').hide();
		$('.btn-trep').click(function(){
			$('#reply').show();
			$('#ph').hide();
		});
		$('.btn-tok').click(function(){
			var datas = '<?php echo Problem::STATUS_RESOLVED ?>';
			var url_href = '<?php echo Router::Url(array('controller'=>'Problems','action'=>'listreport2')); ?>';
			var id   = '<?php echo $issue_id ?>';
			var status = confirm('<?php echo __("Bạn đã tự giải quyết và không cần trợ giúp nữa. Đồng ý đóng báo lỗi này ?");?>');
			if (status == true) {
				return change_status(id, datas, url_href);
			} else {
				return false;
			}
		});
		$('.btn-topen').click(function(){
			var datas = '<?php echo Problem::STATUS_NEW ?>';
			var id   = '<?php echo $issue_id ?>';
			var url_href = '<?php echo Router::Url(array('controller'=>'Problems','action'=>'detail_issue', $issue_id)); ?>';
			var status = confirm('<?php echo __("Lỗi này chưa được giải quyết hoàn toàn, tôi muốn tiếp tục được hỗ trợ ?");?>');
			if (status == true) {
				return change_status(id, datas, url_href);
			} else {
				return false;
			}
		});
		var url = '<?php echo Router::Url(array('controller' => 'Problems', 'action' => 'detail_issue', $issue_id)); ?>';
		var number_record = 5;
		var start         = 5;
		var text_default  = $('.tick-moreI').text();
		var loading       = '<?php echo __('Loading ...')?>';
		var total         = <?php echo $total;?>;
		var page          = Math.floor(total/start);
		var du            = total%start;
		var counter       = 0;
		if (total <= start) {
			$('.tick-moreI').remove();
		}
		$('.tick-moreI').click(function(){
			counter += 1;
			if (!$(this).hasClass('clicked')) {
				$(this).addClass('clicked').text(loading);
				$.ajax({
					type: "POST",
					url: url,
					dataType: 'html',
					data: {start:start},
					success : function(result) {
						if (result) {
							$('#load').after(result);
							$('.tick-moreI').removeClass('clicked').text(text_default);
							start += number_record;
						} else {
							$('.tick-moreI').remove();
						}
					}
				})
			}
			if (page == 1) {
				$('.tick-moreI').remove();
			} else {
				if (du != 0) {
					if (page == counter) {
						$('.tick-moreI').remove();
					}
				} else {
					if (page == (counter+1)) {
						$('.tick-moreI').remove();
					}
				}
			}
		});
		function change_status(id, datas, url_href) {
			var url  = '<?php echo Router::Url(array('controller'=>'Problems','action'=>'change_status')); ?>';
			if (id != '' && datas != '') {
				$.ajax({
					type: 'POST',
					url: url,
					dataType: 'json',
					data: {datas: datas, id: id},
					success: function (result) {
						window.location.href = url_href;
					}
				});
			}
		}
	})
</script>