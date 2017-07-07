<body class="payment-page">
<section id="wrapper">
    <?php
        if ( !empty($vcurrency_type) && $vcurrency_type == 'diamond')
            echo '<article class="content global kimcuong">';
        else
            echo '<article class="content global">';
    ?>
        <div id="payment_scroller" class="payop">
            <div id="scroller">
                <ul class="payment-item-list">
                    <?php
                        if (!empty($products)) {
                            foreach($products as $product) {
                                echo '<li class="payment-item">';
                                echo '<a href="javascript:MobAppSDKexecute(\'mobPaymentStartPayPal\', {amount: ' . $product['provider_price'] . ', currency: \'USD\', description: \'' . __('Nạp %d vàng', $product['game_price']) . '\', accept_credit_cards: true})">';
                                echo '<span class="cost"><i></i>' . $this->Number->format($product['game_price'], array('thousands'=>',','before' => '','places' => 0)) . '</span>';
                                echo '<span class="price" href="#">';
                                echo $this->Number->currency($product['provider_price'], $product['currency_display'], array('places' => 2));
                                echo '</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                        }

                    ?>
                </ul>
            </div>
        </div>
        <div class="footer">
        </div>
    </article>
</section>