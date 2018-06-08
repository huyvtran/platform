<?php 
$this->extend('/Common/blank');
//debug($data);die;
?>

<div class='row'>
<div class='span12'>
    <table class="table table-striped">
        <tr>
            <th>id</th>
            <th>order_id</th>
            <th>card_code</th>
            <th><?php echo 'card_serial'; ?></th>
            <th><?php echo 'price'; ?></th>
            <th><?php echo 'type'; ?></th>
            <th><?php echo 'chanel'; ?></th>
            <th><?php echo 'note'; ?></th>
            <th><?php echo 'status'; ?></th>
            <th><?php echo 'created'; ?></th>
            <th><?php echo 'modified'; ?></th>
        </tr>

        <?php foreach ($data as $payment): ?>
            <?php
                $style = "";
                if( !empty($payment['WaitingPayment']['test']) ) $style = "color: red;"
            ?>
            <tr style="<?php echo $style; ?>">
                <td><?php echo h($payment['WaitingPayment']['id']); ?>&nbsp;</td>
                <td> <?php echo $payment['WaitingPayment']['order_id']; ?> </td>
                <td> <?php echo $payment['WaitingPayment']['card_code']; ?> </td>
                <td> <?php echo $payment['WaitingPayment']['card_serial']; ?> </td>
                <td> <?php if( !empty($payment['Payment']['price']) ) echo number_format($payment['Payment']['price'], 0, '.', ','); ?> </td>
                <td> <?php echo $payment['WaitingPayment']['type']; ?> </td>
                <td>
                    <?php
                    $chanel = '';
                    if( !empty($payment['WaitingPayment']['chanel']) ) {
                        switch ($payment['WaitingPayment']['chanel']) {
                            case Payment::CHANEL_VIPPAY :
                                $chanel = 'Vippay';
                                break;
                            case Payment::CHANEL_VIPPAY_2 :
                                $chanel = 'Vippay 2';
                                break;
                            case Payment::CHANEL_VIPPAY_3 :
                                $chanel = 'Vippay 3';
                                break;
                            case Payment::CHANEL_HANOIPAY :
                                $chanel = 'Hanoipay';
                                break;
                            case Payment::CHANEL_PAYPAL :
                                $chanel = 'Paypal';
                                break;
                            case Payment::CHANEL_ONEPAY :
                                $chanel = '1Pay';
                                break;
                            case Payment::CHANEL_ONEPAY_2 :
                                $chanel = '1Pay 2';
                                break;
                            case Payment::CHANEL_PAYMENTWALL :
                                $chanel = 'PayWall';
                                break;
                            case Payment::CHANEL_APPOTA :
                                $chanel = 'Appota';
                                break;
                            case Payment::CHANEL_INPAY :
                                $chanel = 'Inpay';
                                break;
                            case Payment::CHANEL_NL_ALE :
                                $chanel = 'Ale/NL';
                                break;
                            case Payment::CHANEL_MANUAL :
                                $chanel = 'Manual';
                                break;
                        }
                    }
                    echo $chanel;
                    ?>
                </td>
                <td> <?php if( !empty($payment['WaitingPayment']['note']) ) echo $payment['WaitingPayment']['note']; ?> </td>
                <td> <?php
                    $status = '';
                    if ( isset($payment['WaitingPayment']['status']) ) {
                        switch ($payment['WaitingPayment']['status']){
                            case WaitingPayment::STATUS_WAIT:
                                $status = '<span class="label label-default">Create</span>';
                                break;
                            case WaitingPayment::STATUS_QUEUEING:
                                $status = '<span class="label label-warning">Wait</span>';
                                break;
                            case WaitingPayment::STATUS_COMPLETED:
                                $status = '<span class="label label-success">OK</span>';
                                break;
                            case WaitingPayment::STATUS_ERROR:
                                $status = '<span class="label label-important">Error</span>';
                                break;
                            case WaitingPayment::STATUS_REVIEW:
                                $status = '<span class="label label-important">Review</span>';
                                break;
                        }

                        echo $status;
                    }
                ?> </td>

                <td> <?php echo $payment['WaitingPayment']['created']; ?> </td>
                <td> <?php echo $payment['WaitingPayment']['modified']; ?> </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging">
        <?php
        echo $this->element('paging');
        ?>
    </div>
</div>
</div>