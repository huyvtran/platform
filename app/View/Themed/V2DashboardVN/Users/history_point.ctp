<section id="wrapper">
	<div class="list-task">
	<?php
		if (!empty($points)) {
			foreach ($points as $funpoint) {
	?>
				<div class="box-task task-card">
					<div class="task-inner">
						<span class="task-ico spr-nv"></span>
						<h3 class="rs task-name">
						<?php echo __("Nạp tiền ") . ' '. number_format($funpoint['0']['point'], 2) ." $ in game " . $funpoint['Game']['title']; ?>
						</h3>
						<p class="rs task-text">Day <?php echo date('d/m/Y', $funpoint['MobOrder']['time']);?></p>
						<a href="javascript:void(0)" class="task-ok spr-nv"></a>
					</div>
					<span class="task-coin"><?php if ($funpoint['0']['point'] > 0) echo '+';?><?php echo round($funpoint['0']['point'] * 20);?></span>
				</div>
	<?php }} else {
			echo '<span class="no_error">No have log points</span>';
		}?>
		<input type="hidden" id="load">
		<?php if ($total > 20) {?>
			<a href="javascript:void(0)" class="list-more"><?php echo __('Xem thêm');?></a>
		<?php }?>
	</div>
</section>
<div id="result"></div>
<script type="text/javascript">
	$(document).ready(function(){
		var url = '<?php echo Router::Url(array('controller' => 'Users', 'action' => 'historyPoint')); ?>';
		var number_record = 20;
		var start         = 20;
		var text_default  = $('.list-more').text();
		var loading       = '<?php echo __('Loading ...')?>';
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
							var title = '';
							var tru = '';
							$.each(result, function(key, value) {
								html += '<div class="box-task task-card">';
								html += '<div class="task-inner">';
								html += '<span class="task-ico spr-nv"></span>';
								html += '<h3 class="rs task-name">';
								html += '<?php echo __('Nạp tiền ') . ' '?>' +  Math.round(value['0']['point']).toFixed(2) + '<?php echo " $ " . __("in game") . ' '?>' + value['Game']['title'];
								html += '</h3><p class="rs task-text">';
								html += 'Day ' + value[0]['created_day'];
								html += '</p>';
								html += '<a href="javascript:void(0)" class="task-ok spr-nv"></a></div>';
								if (value['0']['point'] > 0) {
									tru = '+';
								}
								html += '<a href="javascript:void(0)" class="task-coin">';
								html += tru;
								html += Math.round(value['0']['point'] * 20);
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