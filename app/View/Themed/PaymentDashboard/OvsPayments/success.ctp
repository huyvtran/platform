<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
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
            <p>Coin: <span style="color: #00b0eb"><?php echo number_format($data_payment['price_end'], 0, '.', ','); ?></span></p>

            <div class="text-center">
                <p style="margin-top: 20px; color: #00b0eb; font-size: 16px;"><?= __("Thanh toán hoàn tất chúc bạn chơi game vui vẻ !") ?></p>
            </div>
        </div>
    </div>
</div>
