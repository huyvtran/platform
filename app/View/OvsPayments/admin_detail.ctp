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
        <h5>1Pay info</h5>
        <ul class='unstyled'>
            <li>order_id   : <?php echo $data['OnepayOrder']['order_id'] ?></li>
            <li>order_info : <?php echo $data['OnepayOrder']['order_info'] ?></li>
            <li>order_type : <?php echo $data['OnepayOrder']['order_type'] ?></li>
            <li>amount     : <?php echo $data['OnepayOrder']['amount'] ?></li>
            <li>card_name  : <?php echo $data['OnepayOrder']['card_name'] ?></li>
            <li>card_type  : <?php echo $data['OnepayOrder']['card_type'] ?></li>
            <li>response_code    : <?php echo $data['OnepayOrder']['response_code'] ?></li>
            <li>trans_status     : <?php echo $data['OnepayOrder']['trans_status'] ?></li>
            <li>created          : <?php echo $data['OnepayOrder']['created'] ?></li>
        </ul>
    </div>
</div>