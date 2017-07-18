<body class="rs">
<div class="m-container">
    <div class="bd" style="background: black;">
        <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">X</a>
    </div>
    <div class="box-lstNap pdm">
        <div class="fixCen bd">
            <h3 style="text-align: center">Banking (visa, master)</h3>
            <div class="lstMG cf">
                <?php if( !empty($products) ){ ?>
                    <?php foreach ($products as $product){?>
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_onepay_order',
                            '?' => array(
                                'app'   => $game['app'],
                                'token' => $token,
                                'productId' => $product['Product']['id']
                            )
                        )); ?>" class="btn-mg">
                            <span class="f-mg"><i class="ico-mg"></i> <?php echo $product['Product']['platform_price']; ?> </span>
                            <span price="<?php echo $product['Product']['price'] ?>" class="f-tien" data-toggle="modal" data-target="#myModal"> <?php echo $product['Product']['price'] ?> $</span>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>