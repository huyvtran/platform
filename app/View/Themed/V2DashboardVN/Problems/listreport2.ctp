<?php
if (isset($this->request->named['id'])) {
	$id = $this->request->named['id'];
} else {
	$id = 0;
}
?>
<div  class="m-container">
	<div class="box-ticket">
		<ul class="rs lstTick cf">
			<li><a href="<?php echo Router::url(array('controller' => 'Problems', 'action' => 'report_4'))?>" class="t-baoloi"><span><?php echo __('Báo Lỗi');?></span></a></li>
			<li><a href="javascript:MobAppSDKexecute('mobOpenContact', {support_email: '<?php echo $currentGame['support_email'] ?>'})" class="t-mail"><span>Email</span></a></li>
		</ul>
		<?php
		if (!empty($data)) {
			foreach($data as $value) {
				$class = '';
				$span = '';
				if ($value['Problem']['user_read'] == Problem::STATUS_READED) {
					$class = 'box-lstTickN';
				}
				if ($value['Problem']['status'] == Problem::STATUS_RESOLVED) {
					$span  = '<span class="t-tt t-close">' . __('Closed') . '</span>';
				} else {
					$span  = '<span class="t-tt">' . __('Open') . '</span>';
				}
				?>
				<a class="box-lstTick <?php echo $class;?>  t-tt3" href="<?php echo Router::url(array('controller' => 'Problems', 'action' => 'detail_issue', $value['Problem']['id'], 'user_read' => Problem::STATUS_READED))?>">
					<i class="t-ico"></i>
			<span class="t-lstTitle">
			<?php
			if (strlen($value['Problem']['title']) > 45 && $currentGame['screen'] == 'vertical') {
				echo $this->Text->truncate($value['Problem']['title'], 43, array('exact' => false));
			} else {
				echo h($value['Problem']['title']);
			}
			?>
			</span>
			<span class="t-lstTime">
				<?php
				if (date('d', strtotime($value['Problem']['created'])) == date('d', time())) {
					echo $this->Nav->display_time($value['Problem']['created']);
				} else {
					echo date('d/m/Y', strtotime($value['Problem']['created']));
				}
				?>
			</span>
            <span class="t-wrap">
				<?php echo $span;?>
	            <span class="t-lstId">Ticket # <?php echo $value['Problem']['id']?></span>
            </span>
			<span class="t-lstUpdate"><?php echo __('Cập nhật');?> :
				<?php
				if (date('d', strtotime($value['Problem']['modified'])) == date('d', time())) {
					echo $this->Nav->display_time($value['Problem']['modified']);
				} else {
					echo date('d/m/Y, H:i A', strtotime($value['Problem']['modified']));
				}
				?>
			</span>
				</a>
			<?php }?>
			<input type="hidden" id="load">
			<?php if ($total > 10) {?>
				<a href="javascript:void(0)" class="list-more"><?php echo __('Xem thêm');?></a>
			<?php }
		}else {?>
			<span class="no_error"><?php echo __('Không có báo lỗi nào');?></span>
		<?php }?>
	</div>
</div>
<script type="text/javascript">
	<?php if ($id != 0) {?>
	MobAppSDKexecute('mobGetIssue', {id: '<?php echo $id ?>'});
	<?php }?>
	$(document).ready(function(){
		var url = '<?php echo Router::Url(array('controller' => 'Problems', 'action' => 'listreport2')); ?>';
		var number_record = 10;
		var start         = 10;
		var text_default  = $('.list-more').text();
		var loading       = '<?php echo __('Loading ...')?>';
		var total         = <?php echo $total;?>;
		var page          = Math.floor(total/start);
		var du            = total%start;
		var counter       = 0;
		var resolved      = '<?php echo Problem::STATUS_RESOLVED?>';
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
							var html = '';
							var class_2 = '';
							var span = '';
							$.each(result, function(key, value) {
								if (value['status'] == resolved) {
									class_2 = 'box-lstTickN';
									span  = '<span class="t-tt t-close"><?php echo __('Closed');?></span>';
								} else {
									span  = '<span class="t-tt"><?php echo __('Open');?></span>';
								}
								html += '<a class="box-lstTick ' + class_2 + 't-tt3" href="/platform/Problems/detail_issue/' + value['id'] + '/user_read:<?php echo Problem::STATUS_READED?>">';
								html += '<i class="t-ico"></i>';
								html += '<span class="t-lstTitle">' + value['title'] + '</span>';
								html += '<span class="t-lstTime">' + value['created'] + '</span>';
								html += '<span class="t-wrap">';
								html += span;
								html += '<span class="t-lstId">Ticket # ' + value['id'] + '</span>';
								html += '</span>';
								html += '<span class="t-lstUpdate"><?php echo __('Cập nhật');?> : ' + value['modified'] + '</span>';
								html += "</a>";
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