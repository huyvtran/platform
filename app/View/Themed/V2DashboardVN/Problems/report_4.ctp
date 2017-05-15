<?php
if (isset($this->request->named['id'])) {
	$id = $this->request->named['id'];
} else {
	$id = 0;
}
?>
<div class="m-container">
	<div class="error_mess">
		<?php echo $this->Session->flash('error');?>
	</div>
	<div class="box-ticket">
		<?php
		$class_card_type = $class_type = $class_title = $class_server = $class_char = $class_serial = $class_des = '';
		if (!empty($error)) {
			if (array_key_exists('type', $error))        $class_type   = 'error';
			if (array_key_exists('title', $error))       $class_title  = 'error';
			if (array_key_exists('server', $error))      $class_server = 'error';
			if (array_key_exists('character', $error))   $class_char   = 'error';
			if (array_key_exists('card_serial', $error)) $class_serial = 'error';
			if (array_key_exists('description', $error)) $class_des    = 'error';
			if (array_key_exists('card_type', $error))   $class_card_type = 'error';
		}
		echo $this->Form->create('Problem', array(
			'url' => array('controller' => 'Problems', 'action' => 'report_4'),
			'class' => 'formTicket',
			'type' => 'file',
			'novalidate' => true,
		));
		?>
		<div class="box-tickH">
			<h3 class="rs"><?php echo __('Loại lỗi')?> <span style="color:#f04307">*</span></h3>
			<div class="tt-row tt-rowtext">
				<?php
				echo $this->Form->input('type', array('options' => $t_error,'error' => false,  'label' => false, 'div' => false, 'id' => 'tick_type', 'class' => $class_type));
				?>
				<span class="icon-down"></span>
			</div>
		</div>
		<div class="box-tickND">
			<div class="box-tickForm">
				<h3 class="rs"><?php echo __('Tiêu đề')?> <span style="color:#f04307">*</span></h3>
				<div class="tt-row tt-rowtext">
					<?php
					echo $this->Form->input('title', array('label' => false, 'error' => false, 'div' => false, 'class' => "tick-input $class_title", 'id' => 'tick_title'));
					?>
					<span class="icon-clear" style="display: none;">x</span>
				</div>
			</div>
			<div class="box-tickForm">
				<h3 class="rs"><?php echo __('Server')?> <span style="color:#f04307">*</span></h3>
				<div class="tt-row tt-rowtext">
					<?php
					echo $this->Form->input('server', array('label' => false, 'error' => false, 'div' => false, 'class' => "tick-input $class_server", 'id' => 'tick_server'));
					?>
					<span class="icon-clear" style="display: none;">x</span>
				</div>
			</div>
			<div class="box-tickForm">
				<h3 class="rs"><?php echo __('Tên nhân vật')?> <span style="color:#f04307">*</span></h3>
				<div class="tt-row tt-rowtext">
					<?php
					echo $this->Form->input('character', array('label' => false, 'error' => false, 'div' => false, 'class' => "tick-input $class_char", 'id' => 'tick_character'));
					?>
					<span class="icon-clear" style="display: none;">x</span>
				</div>
			</div>
			<div class="box-tickForm card">
				<h3 class="rs"><?php echo __('Loại thẻ cào')?> <span style="color:#f04307">*</span></h3>
				<div class="tt-row tt-rowtext">
					<?php
					echo $this->Form->input('card_type', array('options' => $type_card, 'error' => false, 'label' => false, 'div' => false, 'id' => 'tick_card_type', 'class' => $class_card_type));
					?>
					<span class="icon-down"></span>
				</div>
			</div>
			<div class="box-tickForm card">
				<h3 class="rs"><?php echo __('Serial thẻ')?> <span style="color:#f04307">*</span></h3>
				<div class="tt-row tt-rowtext">
					<?php
					echo $this->Form->input('card_serial', array('label' => false, 'error' => false, 'div' => false, 'class' => "tick-input $class_serial", 'id' => 'tick_card_serial'));
					?>
					<span class="icon-clear" style="display: none;">x</span>
				</div>
			</div>
			<div class="box-tickForm">
				<h3 class="rs"><?php echo __('Chi tiết')?> <span style="color:#f04307">*</span></h3>
				<?php
				echo $this->Form->input('description', array('label' => false, 'error' => false,'div' => false, 'id' => 'tick_desc', 'rows' => 8, 'cols' => 15, 'class' => $class_des));
				?>
			</div>
			<?php if($currentGame['language_default'] == 'vie' && $check == true) {?>
				<div class="box-tickForm">
					<h3 class="rs"><?php echo __('SĐT liên hệ')?></h3>
					<div class="tt-row tt-rowtext">
						<?php
						echo $this->Form->input('phone', array('label' => false, 'error' => false, 'div' => false, 'class' => 'tick-input', 'id' => 'tick_phone', 'value' => $phone));
						?>
						<span class="icon-clear" style="display: none;">x</span>
					</div>
				</div>
			<?php }?>
			<div id="img_preview"></div>
			<div class="box-tickBt cf">
                <?php if (!in_array($currentGame['id'], array('238', '230'))) {?>
				<?php if (($currentGame['os'] == 'ios' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '5'))
                        || ($currentGame['os'] == 'android' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '4'))
                ) {?>
				<a href="javascript:MobAppSDKexecute('loadImageUpload', {'function' : 'getImageData'})" id="fileSelect" class="tick-btn tick-file"><?php echo __('Chọn file');?></a>
				<?php } else if (in_array($currentGame['id'], array('230'))) {?>
                    <a href="javascript:MobAppSDKexecute('loadImageUpload', {'function' : 'getImageData'})" id="fileSelect" class="tick-btn tick-file"><?php echo __('Chọn file');?></a>
                <?php }}?>
				<?php
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
		</div>
		<?php echo $this->Form->end();?>
	</div>
</div>
<script>
	<?php
        if (!in_array($currentGame['id'], array('238', '230'))) {
            if (($currentGame['os'] == 'ios' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '5'))
            || ($currentGame['os'] == 'android' && ($sdk_ver[1] >= '4' && $sdk_ver[2] >= '4'))
            ) {
    ?>
	<?php if ($id != 0) {?>
	MobAppSDKexecute('mobGetIssue', {id: '<?php echo $id ?>'});
	<?php } else { ?>
	MobAppSDKexecute('mobGetError');
	<?php }}}?>
	var arr = new Array();
	function getImageData(base64, id) {
		var preview = document.getElementById("img_preview");
		preview.innerHTML = '';
		arr[id] = base64;
		for (var key in arr) {
			var img = document.createElement('img');
			var a   = document.createElement('a');
			var div = document.createElement('div');
			a.setAttribute('href', "javascript:MobAppSDKexecute('deleteImageData', {'index' : '" + key + "'})");
			a.setAttribute('id', 'img' + key);
			a.setAttribute('class', 'img_delete');
			a.setAttribute('data_index', key);
			a.textContent = 'Xóa';
			img.setAttribute('height', '50px');
			img.setAttribute('width', '50px');
			img.setAttribute('src', "data:image/png;base64," + arr[key]);
			div.setAttribute('id', 'image' + key);
			div.setAttribute('class', 'img_pre');
			div.appendChild(a);
			div.appendChild(img);
			preview.appendChild(div);
			document.getElementById("img" + key).addEventListener("click", function(){
				var index = this.getAttribute('data_index');
				delete arr[index];
				document.getElementById('image' + index).remove();
				this.remove();
			});
		}
	}
	$(document).ready(function(){
		$('.card').hide();
		if ($('#tick_type').val() == 'card') {
			$('.card').show();
		} else {
			$('.card').hide();
		}
		$('#tick_type').change(function(){
			if ($(this).val() == 'card') {
				$('.card').show();
			} else {
				$('.card').hide();
			}
		});
		$('#xoa').click(function(){
			var body      = $('#tick_desc').val();
			var title     = $('#tick_title').val();
			var type      = $('#tick_type').val();
			var server    = $('#tick_server').val();
			var character = $('#tick_character').val();
			var card_type = '';
			var card_serial = '';
			if ($('.card').is(":visible")) {
				card_type    = $('#tick_card_type').val();
				card_serial  = $('#tick_card_serial').val();
			}
			if (body != '' || title != '' || type != '' || server != '' || character != '' || card_type != '' || card_serial != ''
				|| $(".img_pre").length != 0
			) {
				var status = confirm('<?php echo __("Bạn có chắc chắn muốn xóa không ?");?>');
				if (status == true) {
					window.location = '<?php echo Router::url(array('controller' => 'problems', 'action' => 'report_4'));?>';
				}
			} else {
				window.location.href = '<?php echo Router::url(array('controller' => 'problems', 'action' => 'listreport2'));?>';
			}
		});
		$(".icon-clear").bind('touchstart click', function(e) {
			e.preventDefault();
			$(this).siblings('input').val('').focus();
			$(this).hide();
		});
		$('.tt-row input').on('blur', function(){
			$(this).siblings('.icon-clear').hide();
		}).on('focus', function(){
			if ($(this).val() !== '') {
				$(this).siblings('.icon-clear').show();
			}
		});
		$("form").on('keyup touchstart', 'input', clearIcon);
		function clearIcon(event) {
			checkShowClearIcon(event.currentTarget);
		}
		function checkShowClearIcon(input) {
			if (input.value == '') {
				$(input).siblings('.icon-clear').hide();
			} else {
				$(input).siblings('.icon-clear').show();
			}
		}
	})
</script>