<?php
$role_id = $area_id = 1;
?>
<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'Payments', 'action' => 'index',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>"><i class="fa fa-chevron-left fa-2x"></i></a>
            </div>

            <?php echo __('Nạp qua thẻ cào'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li> <?php echo __('Cách nạp'); ?></li>
        <li> <?php echo __('Chọn gói'); ?> </li>
        <li class="active"><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <div class="container page-wrapper">
		<div style="max-width: 450px; margin: auto; font-weight: bold; color: #868888;">
			<p><?= __("Mã giao dịch") ?>: <span style="color: #00b0eb"><?= $data_payment['order_id'] ?></span></p>
			<p><?= __("Nạp") ?>: <span style="color: #00b0eb"><?= $data_payment['price_end'] ?>Coin</span></p>
			<p><?= __("Nhận") ?>: <span style="color: #00b0eb"><?= $data_payment['price_game'] ?> KNB</span></p>

			<div class="text-center">
				<p style="margin-top: 20px; color: #00b0eb; font-size: 16px;"><?= __("Thanh toán hoàn tất chúc bạn chơi game vui vẻ !") ?></p>
				<a class="btn btn-primary" href="<?php echo $this->Html->url(array( 'controller' => 'Payments', 'action' => 'index',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>"><?= __("Quay lại") ?></a>
			</div>
		</div>
    </div>
</div>
