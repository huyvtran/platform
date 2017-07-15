<body class="rs">
<div class="m-container">
    <div class="box-lstNap pdm">
        <div class="fixCen bd">
            <h3 style="text-align: center">Paypal</h3>
            <div class="lstMG cf">
                <?php if( !empty($products) ){ ?>
                    <?php foreach ($products as $product){?>
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paypal_order',
                            '?' => array(
                                'app'   => $game['app'],
                                'token' => $token,
                                'productId' => $product['Product']['id']
                            )
                        )); ?>" class="btn-mg">
                            <span class="f-knb "><i class="ico-mg"></i> <?php echo $product['Product']['price'] ?> </span>
                            <span price="<?php echo $product['Product']['price'] ?>" class="f-tien" data-toggle="modal" data-target="#myModal"> <?php echo $product['Product']['platform_price'] ?> </span>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>