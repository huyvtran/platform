<?php
$role_id = $area_id = 1;
?>
<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
                <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false"><i class="fa fa-home fa-2x"></i></a>
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

        <?php if( !$this->Nav->hideFunction('hide_payment', $game) ){ ?>

            <?php
            $Redis = new RedisQueue('default');
            $Redis->key = 'payment-manual-sweb-status-' . Payment::TYPE_NETWORK_GATE;
            $mobi_data = $Redis->lRange(0, -1);
            if( !empty($mobi_data[0]['status']) ){ // cổng sweb
                ?>
                <a href="<?php echo $this->Html->url(array( 'controller' => 'ManualPayments', 'action' => 'sweb',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'type'  => Payment::TYPE_NETWORK_GATE,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>" class="card-type">
					<span class="card-icon">
						<img src="/payment/images/logo_gate.png" alt="Mobile Card">
					</span>
                    <span> <?php echo __('Recharge by Gate'); ?> <strong style="color: red">+10%</strong> </span>
                </a>
            <?php } ?>

            <?php
            $Redis = new RedisQueue('default');
            $Redis->key = 'payment-mobo-status-' . Payment::TYPE_NETWORK_GATE;
            $mobi_data = $Redis->lRange(0, -1);
            if( !empty($mobi_data[0]['status']) ){ // cổng mobo
                ?>
                <a href="<?php echo $this->Html->url(array( 'controller' => 'ManualPayments', 'action' => 'mobo',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'type'  => Payment::TYPE_NETWORK_GATE,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>" class="card-type">
					<span class="card-icon">
						<img src="/payment/images/logo_gate.png" alt="Mobile Card">
					</span>
                    <span> <?php echo __('Recharge by Gate'); ?> <strong style="color: red">+30%</strong> </span>
                </a>
            <?php } ?>

            <?php
            App::import('Lib', 'RedisCake');
            $Redis2 = new RedisCake('action_count');
            $paypal_enable = $Redis2->get('payment-paypal-enable');
            $paypal_enable = 1;
            if( !empty($paypal_enable) ){ // cổng paypal
            ?>
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
                    <span> <?php echo __('Recharge by Paypal'); ?> </span>
                </a>
            <?php } ?>
		
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
                <span> <?php echo __('Recharge by Visa/Master'); ?> </span>
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
                <span> <?php echo __('Recharge by Banking'); ?> </span>
            </a>
        <?php } ?>
    </div>
</div>