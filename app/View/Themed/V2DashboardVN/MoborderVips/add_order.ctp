<style>
	.error-message{
		color:red;
	}
</style>
<?php
$banks = array(
	'' => '-- Chọn Ngân Hàng --',
	'VietinBank'  => 'VietinBank',
	'BIDV'        => 'BIDV',
	'VPBank'      => 'VPBank',
	'Agribank'    => 'Agribank',
	'Vietcombank' => 'Vietcombank',
	'ACB'         => 'ACB',
	'MBBank'      => 'MBBank',
	'Sacombank'   => 'Sacombank',
	'Maritimebank' => 'Maritimebank',
);
if (isset($phone_number) && $phone_number != '') {
	$phone_fill = $phone_number;
} else {
	$phone_fill = $this->Session->read('Auth.User.phone');
}
?>
<section id="wrapper">
	<div class="pop-napho">
		<div class="dialog">
			<h3>Thanh toán trực tiếp qua NH không giới hạn</h3>
			<p class="note">Vui lòng điền đầy đủ các thông tin dưới đây. Sau khi nhận được yêu cầu, chúng tôi sẽ liên hệ ngay với bạn qua email hoặc SĐT bạn đã cung cấp.</p>
			<?php echo $this->Form->create('MoborderVip', array('url' => array('controller' => 'MoborderVips', 'action' => 'addOrder', 'div' => false))); ?>
			<?php
			$error = $this->Session->flash('error');
			if ($error != false && !empty($error)) {
				?>
				<div class="error_mess"><?php echo $error;?></div>
				<?php
			}
			?>
			<div class="group">
				<label for="phone">SĐT <span class="required">*</span></label>
				<div class="input-wrapper">
					<i class="icon icon-clear" >&#10006;</i>
					<?php
					echo $this->Form->input('phone', array('id' => 'phone', 'error' => false, 'type' => 'tel', 'class' => 'input', 'label' => false, 'div' => false, 'value' => isset($phone_fill) ? $phone_fill : ''));
					?>
				</div>
			</div>
			<div class="group">
				<label for="money">Số tiền <span class="required">*</span></label>
				<div class="input-wrapper">
					<i class="icon icon-clear" >&#10006;</i>
					<?php
					echo $this->Form->input('money', array('id' => 'money', 'placeholder' => 'Số tiền tối thiều là 500.000 VNĐ', 'error' => false, 'type' => 'tel', 'class' => 'input', 'label' => false, 'div' => false, 'value' => !empty($this->request->data['MoborderVip']['money']) ? number_format($this->request->data['MoborderVip']['money']) : ''));
					?>
				</div>
			</div>
			<div class="group">
				<label for="OrderNote">Ngân Hàng <span class="required">*</span></label>
				<div class="input-wrapper">
					<i class="icon icon-dropdown"></i>
					<?php
					echo $this->Form->input('bank', array('class' => 'input', 'label' => false, 'div' => false, 'error' => false, 'options' => $banks));
					?>
				</div>
			</div>
			<?php
			echo $this->Form->submit('Gửi yêu cầu', array('class' => 'btn btn-orange'));
			echo $this->Form->button('Xóa', array('type' => 'reset', 'class' => 'btn btn-gray'));
			echo $this->Form->end();
			?>
		</div>
	</div>
</section>
<script>
	$(document).ready(function(){
		function RemoveRougeChar(convertString){
			if(convertString.substring(0,1) == ","){
				return convertString.substring(1, convertString.length);
			}
			return convertString;
		}
		$('#money').keyup(function(event){
			if(event.which >= 37 && event.which <= 40){
				event.preventDefault();
			}
			var $this = $(this);
			var num = $this.val().replace(/,/gi, "").split("").reverse().join("");
			var num2 = RemoveRougeChar(num.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
			$this.val(num2);
		});
		$('#money').keypress(function(event){
			var charCode = (event.which) ? event.which : event.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				event.preventDefault();
			}
		});
		$('#phone').keypress(function(event){
			var charCode = (event.which) ? event.which : event.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				event.preventDefault();
			}
		});

		$(".icon-clear").bind('touchstart click', function(e) {
			e.preventDefault();
			$(this).siblings('input').val('').focus();
			$(this).hide();
		});
		$('.input-wrapper input').on('blur', function(){
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