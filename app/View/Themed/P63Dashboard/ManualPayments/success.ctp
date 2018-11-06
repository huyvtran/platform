<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
            </div>

            <?php echo __('Nạp qua thẻ cào'); ?>

            <div class="nav navbar-right">
                <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false"><i class="fa fa-close fa-2x"></i></a>
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
            <?php if(!empty($data_payment['order_id'])){ ?>
            <p><?= __("Mã giao dịch") ?>: <span style="color: #00b0eb"><?= $data_payment['order_id'] ?></span></p>
            <p>Coin: <span style="color: #00b0eb"><?php echo number_format($data_payment['price_end'], 0, '.', ','); ?></span></p>
            <?php } ?>
            <div class="text-center">
                <p style="margin-top: 20px; color: #00b0eb; font-size: 16px;"><?= __("The transaction was successfully.") ?></p>
            </div>
        </div>
    </div>
</div>
