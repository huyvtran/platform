<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Payment Detail Management</h3>
<div class="row">
    <div class='span6'>
        <h5>Reponse</h5>
        <ul class='unstyled'>
            <?php if( !empty( $result['obj']['errorDesc'] )){?>
            <li>description   : <?php echo $result['obj']['errorDesc']; ?></li>
            <?php } ?>
            <li>order_id   : <?php echo $result['order_id']; ?></li>
            <li>card_code : <?php echo $result['card_code'] ?></li>
            <li>card_serial : <?php echo $result['card_serial'] ?></li>
            <li>type  : <?php echo $result['type'] ?></li>
            <li>giá tiền     : <?php echo $result['obj']['amount'] ?></li>
        </ul>
    </div>
</div>

<div class="actions">
    <h3><?php echo 'Actions'; ?></h3>
    <ul>
        <li><?php echo $this->Html->link('New', array('action' => 'inpay')); ?></li>
    </ul>
</div>