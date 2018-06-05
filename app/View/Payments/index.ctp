<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
                <a href="#"><i class="fa fa-home fa-2x"></i></a>
            </div>

            <?php echo __('Nạp thẻ'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li class="active"><?php echo __('Cách nạp'); ?></li>
        <li><?php echo __('Chọn gói'); ?></li>
        <li><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <div class="container page-wrapper">
        <a href="<?php echo $this->Html->url([
            'controller' => 'Payments',
            'action' => 'inapp',
            '?' => [
                'app'   => $game['app'],
                'token' => $token
            ]
        ]); ?>" class="card-type">
        <span class="card-icon">
            <img src="/payment/images/apple-store.png" alt="Apple Store">
        </span>
            <span> <?php echo __('Nạp từ store'); ?> </span>
        </a>

        <?php if( !$this->Nav->hideFunction('hide_payment', $game) ){ ?>
            <a href="<?php echo $this->Html->url([
                'controller' => 'Payments',
                'action' => 'recharge',
                '?' => [
                    'app'   => $game['app'],
                    'token' => $token
                ]
            ]); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/bag.png" alt="Mobile Card">
                </span>
                <span> <?php echo __('Rút tiền game'); ?> </span>
            </a>

            <a href="<?php echo $this->Html->url([
                'controller' => 'Payments',
                'action' => 'choose_pay',
                '?' => [
                    'app'   => $game['app'],
                    'token' => $token
                ]
            ]); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/credit-card-orange.png" alt="Mobile Card">
                </span>
                <span> <?php echo __('Phương thức nạp'); ?> </span>
            </a>
        <?php } ?>
    </div>
</div>