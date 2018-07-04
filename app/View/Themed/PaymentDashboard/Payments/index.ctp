<?php
$role_id = $area_id = 1;
?>
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
        <?php if(!empty($currentGame['data']['payment']['notice'])){ ?>
            <center><span style="color: red;"><?= $currentGame['data']['payment']['notice']; ?></span></center><br/>
        <?php } ?>

        <?php if(!empty($this->Session->flash('payment'))){ ?>
            <center><span style="color: red;"><?= $this->Session->flash('payment'); ?></span></center><br/>
        <?php } ?>

        <a href="<?php echo $this->Html->url([
            'controller' => 'Payments',
            'action' => 'inapp',
            '?' => [
                'app'   => $game['app'],
                'token' => $token,
                'role_id'   => $role_id,
                'area_id'   => $area_id
            ]
        ]); ?>" class="card-type">
        <span class="card-icon">
            <img src="/payment/images/apple-store.png" alt="Apple Store">
        </span>
            <span> <?php echo __('Nạp từ store'); ?> </span>
        </a>

        <?php if($this->Session->read('Auth.User.id') == 289914){ ?>
        <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paypal_index',
            '?' => array(
                'app'   => $currentGame['app'],
                'token' => $token,
                'role_id'   => $role_id,
                'area_id'   => $area_id
            )
        )); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/paypal.png" alt="Mobile Card">
                </span>
            <span> <?php echo __('Nạp từ Paypal'); ?> </span>
        </a>
        <?php } ?>

        <?php if( !$this->Nav->hideFunction('hide_payment', $game) ){ ?>
            <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_ale_index',
                '?' => array(
                    'app'   => $currentGame['app'],
                    'token' => $token,
                    'role_id'   => $role_id,
                    'area_id'   => $area_id
                )
            )); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/credit-card.png" alt="Mobile Card">
                </span>
                <span> <?php echo __('Nạp từ Visa/Master'); ?> </span>
            </a>

            <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_bank',
                '?' => array(
                    'app'   => $currentGame['app'],
                    'token' => $token,
                    'role_id'   => $role_id,
                    'area_id'   => $area_id
                )
            )); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/bank.png" alt="Mobile Card">
                </span>
                <span> <?php echo __('Nạp từ Banking'); ?> </span>
            </a>

            <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_card',
                '?' => array(
                    'app'   => $currentGame['app'],
                    'token' => $token,
                    'role_id'   => $role_id,
                    'area_id'   => $area_id
                )
            )); ?>" class="card-type">
                <span class="card-icon">
                    <img src="/payment/images/sms.png" alt="Mobile Card">
                </span>
                <span> <?php echo __('Nạp từ Card/SMS'); ?> </span>
            </a>
        <?php } ?>
    </div>
</div>