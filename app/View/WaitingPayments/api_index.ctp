
<div class="container body">
    <div class="content">
        <div class='row'>
            <div class='span12'>
                <table class="table table-striped">
                    <tr>
                        <th>order_id</th>
                        <th>card_code</th>
                        <th><?php echo 'card_serial'; ?></th>
                        <th><?php echo 'price'; ?></th>
                        <th><?php echo 'type'; ?></th>
                        <th><?php echo 'status'; ?></th>
                        <th><?php echo 'modified'; ?></th>
                    </tr>

                    <?php foreach ($data as $payment): ?>
                        <?php
                            $style = "";
                            if( !empty($payment['WaitingPayment']['test']) ) $style = "color: red;"
                        ?>
                        <tr style="<?php echo $style; ?>">
                            <td> <?php echo $payment['WaitingPayment']['order_id']; ?> </td>
                            <td> <?php echo $payment['WaitingPayment']['card_code']; ?> </td>
                            <td> <?php echo $payment['WaitingPayment']['card_serial']; ?> </td>
                            <td> <?php if( !empty($payment['Payment']['price']) ) echo number_format($payment['Payment']['price'], 0, '.', ','); ?> </td>
                            <td>
                                <?php
                                $chanel = $payment['WaitingPayment']['type'];
                                if( !empty($payment['WaitingPayment']['chanel']) ) {
                                    switch ($payment['WaitingPayment']['chanel']) {
                                        case Payment::CHANEL_PAYPAL :
                                            $chanel = 'Paypal';
                                            break;
                                        case Payment::CHANEL_GOOGLE :
                                            $chanel = 'Google Inapp';
                                            break;
                                    }
                                }
                                echo $chanel; 
                                ?> </td>

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

                            <td> <?php echo $payment['WaitingPayment']['modified']; ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>