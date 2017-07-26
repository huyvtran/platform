<body class="rs">
<div class="m-container">
    <div class="fixCen bd">
        <div class="box-lstNap pdm">
            <h3></h3>
            <div class="lstNap cf">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'payments', 'action' => 'pay',
                    '?' => array(
                        'app'   => $game['app'],
                        'token' => $token
                    )
                )); ?>" class="btn-thecao">
                    <i></i>
                    Nạp Thẻ Cào
                </a>

                <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paypal_index',
                    '?' => array(
                        'app'   => $game['app'],
                        'token' => $token
                    )
                )); ?>" class="btn-paypal">
                    <i></i>
                    Thẻ paypal
                </a>

                <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_onepay_index',
                    '?' => array(
                        'app'   => $game['app'],
                        'token' => $token
                    )
                )); ?>" class="btn-diem">
                    <i></i>
                    Banking
                </a>

                <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_fortumo_index',
                    '?' => array(
                        'app'   => $game['app'],
                        'token' => $token
                    )
                )); ?>" class="btn-paypal">
                    <i></i>
                    Fortumo
                </a>
            </div>
        </div>
    </div>
</div>
</body>