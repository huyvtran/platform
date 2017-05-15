<section id="wrapper">
	<article class="content global">
		<?php if (!empty($paymentWallet)) {?>
		<div id="payment_scroller">
			<div id="scroller">
				<span class="text-tb">Tổng số điểm của bạn là: <strong><?php echo (!empty($paymentWallet) && $paymentWallet['PaymentWallet']['money'] > 0) ? number_format($paymentWallet['PaymentWallet']['money'], 0, ',', '.') : 0?></strong></span>
				<ul class="payment-item-list">
					<?php
					if (!empty($product)) {
						foreach ($product as $value) {
							?>
							<li class="payment-item">
								<a class="cd-popup-trigger" data-price="<?php echo number_format($value['Product']['price'], 0, ',', '.');?>" data-id="btn1" href="<?php echo Router::url(array('controller' => 'PaymentWallets', 'action' => 'PaymentWallet_pay', $value['Product']['id']));?>">
									<span class="cost"><i></i><?php echo number_format($value['Product']['game_price'], 0, ',', '.')?></span>
									<span class="price" href="javascript:void(0)"><?php echo number_format($value['Product']['price'], 0, ',', '.')?></span>
								</a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
		<?php if (!empty($log_payment)) {?>
			<div class="box-ls">
				<h3 class="rs">Lịch sử điểm</h3>
				<table class="tbList">
					<tbody>
					<tr>
						<th class="">Tiêu đề</th>
						<th class="">Thời Gian</th>
					</tr>
					<?php foreach ($log_payment as $value) {?>
						<tr>
							<td><?php echo $value['LogPaymentWallet']['reason']?></td>
							<td><?php echo date('H:i d-m-Y', strtotime($value['LogPaymentWallet']['created']));?></td>
						</tr>
					<?php }?>
					</tbody>
				</table>
			</div>
		<?php }?>
		<?php } else {?>
			<span class="text-tbb">Tính năng thanh toán này đang bị khóa với tài khoản của bạn. Vui lòng liên hệ Hotline: 094 318 8364 để được hỗ trợ trực tiếp</span>
		<?php }?>
		<div class="footer">
			<ul class="rs lstF">
				<li><?php echo __('Gặp sự cố khi nạp tiền?'); ?> Vui lòng liên hệ hotline: 094 318 8364</li>
				<li><?php echo __('Hoặc gửi email tới'); ?> <?php echo ': hotro@funtap.vn'?></li>
			</ul>
		</div>
	</article>
</section>