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
                        'token' => $token
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
			<div class="text-center">
				<p style="margin-top: 20px; color: #00b0eb; font-size: 16px;"><?= __('Giao dịch thất bại') ?></p>
				<button class="btn btn-primary">Quay lại</button>
			</div>
		</div>
    </div>
</div>
