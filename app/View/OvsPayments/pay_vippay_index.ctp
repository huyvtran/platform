<body class="rs">
<div class="m-container">
    <div class="box-lstNap pdm">
        <div class="fixCen bd">
            <h3>Visa banking</h3>
            <div class="lstMG cf">
                <?php if( !empty($products) ){ ?>
                    <?php foreach ($products as $product){?>
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_vippay_order',
                            '?' => array(
                                'app'   => $game['app'],
                                'token' => $token,
                                'productId' => $product['Product']['id'],
                                'bank_type' => 'Visa'
                            )
                        )); ?>" class="btn-mg">
                            <span class="f-knb "><i class="ico-mg"></i> <?php echo number_format($product['Product']['platform_price'], 0, '.', ',') ?> </span>
                            <span price="<?php echo number_format($product['Product']['platform_price'], 0, '.', ',') ?>" class="f-tien" data-toggle="modal" data-target="#myModal"> <?php echo number_format($product['Product']['platform_price'], 0, '.', ',') ?> vnđ </span>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>