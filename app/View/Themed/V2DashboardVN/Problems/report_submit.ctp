<div class="m-container">
	<div class="error_mess"></div>
	<div class="box-ticket">
		<form action="#" class="formTicket" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<h3 class="rs">Tiêu đề <span style="color:#f04307">*</span></h3>
			<div class="tt-row tt-rowtext">
				<input name="" class="tick-input " id="tick_title" maxlength="255" type="text" required="required"/>
				<span class="icon-clear" style="display: none;">x</span>
			</div>
			<div class="box-tickForm">
				<h3 class="rs">Chi tiết <span style="color:#f04307">*</span></h3>
				<textarea name="" id="tick_desc" rows="8" cols="15" class="" required="required"></textarea>
			</div>
			<div class="box-tickBt box-center cf">
				<button class="tick-btn tick-sent" name="submit" type="submit">OK</button>
			</div>

		</form>
	</div>
	<div class="box-smb" style="display: none">
		Thank you for contacting us
	</div>
</div>

<script>
	$(function() {
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
		$("form").submit(function(){
			var tick_title = $('#tick_title').val();
			var text = $('#tick_desc').val();
			if (tick_title != ''){
				$('#tick_title').parent('.tt-row').removeClass('err');
			} else {
				$('#tick_title').parent('.tt-row').addClass('err');
				return false;
			}
			if (text != ''){
				$('#tick_desc').removeClass('err');
			} else {
				$('#tick_desc').addClass('err');
				return false;
			}
			if (tick_title != '' && text != '') {
				$('.box-ticket').hide();
				$('.box-smb').show();
				return false;
			}

		})
	});

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

</script>