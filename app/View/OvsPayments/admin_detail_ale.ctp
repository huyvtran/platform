<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Payment Detail Management</h3>
<div class="row">
    <div class='span4'>
        <h5>User</h5>
        <ul class='unstyled'>
            <li>user id  : <?php echo $data['User']['id'] ?></li>
            <li>username : <?php echo substr($data['User']['username'], 4) ?></li>
            <li>Game play: <?php echo substr($data['Game']['title'], 5) . ' ' . $data['Game']['os']; ?></li>
            <li>Country  : <?php echo $data['User']['country_code'] ?></li>
            <li>Created  : <?php
                echo $this->Time->timeAgoInWords($data['User']['created'], array(
                    'end' => '1 year',
                    'accuracy' => array('day' => 'day', 'week' => 'week', 'month' => 'month')
                ));
                ?>
            </li>
            <li>order pay : <?php echo $data['WaitingPayment']['order_id'] ?></li>
            <li>price pay : <?php echo $data['WaitingPayment']['price'] ?></li>
            <?php
            if ($data['WaitingPayment']['status'])
                $status =  "<span style='color:green'>Success</span>";
            else
                $status = "<span style='color:red'>Error</span>";
            ?>
            <li>status    : <?php echo $status ?></li>
            <li>time start: <?php echo $data['WaitingPayment']['created'] ?></li>
            <li>time end  : <?php echo $data['WaitingPayment']['modified'] ?></li>
        </ul>
    </div>

    <div class='span6'>
        <h5>Ale info</h5>
        <ul class='unstyled'>
            <?php
                $aleObj = json_decode($data[$model]['nl_data'], true);
                $aleRes = json_decode($data[$model]['buyer_data'], true);
            ?>
            <li>transactionCode   : <?php echo $aleObj['transactionCode'] ?></li>
            <li>orderCode : <?php echo $aleObj['orderCode'] ?></li>
            <li>amount : <?php echo $aleObj['amount'] ?></li>
            <li>currency     : <?php echo $aleObj['currency'] ?></li>
            <li>buyerEmail     : <?php echo $aleObj['buyerEmail'] ?></li>
            <li>buyerPhone     : <?php echo $aleObj['buyerPhone'] ?></li>
            <li>buyerName     : <?php echo $aleObj['buyerName'] ?></li>
            <li>buyerAddress     : <?php echo $aleRes['buyer_address'] ?></li>
            <li>buyerCity     : <?php echo $aleRes['buyer_city'] ?></li>
            <li>buyerCountry     : <?php echo $aleRes['buyer_country'] ?></li>
            <li>status     : <?php echo $aleObj['status'] ?></li>
            <li>message     : <?php echo $aleObj['message'] ?></li>
            <li>installment     : <?php echo $aleObj['installment'] ?></li>
            <li>is3D     : <?php echo $aleObj['is3D'] ?></li>
            <li>month     : <?php echo $aleObj['month'] ?></li>
            <li>method     : <?php echo $aleObj['method'] ?></li>
        </ul>
    </div>
</div>