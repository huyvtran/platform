<?php 
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Management </h3>
    </div>
</div>

<div class='row'>
    <div class='span12'>
        <div class='span3'>
            <?php
            echo $this->Form->create('Payment', array(
                'action' => 'index',
                'class' => 'simple',
                'style' => 'width:200px;'
            ));

            echo $this->Form->input('game_id.', array(
                'type' => 'select',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Game'
                ),
                'multiple',
                'options' => $games,
                'selected' => !empty($this->request->params['named']['game_id']) ? $this->request->params['named']['game_id'] : ''
            ));

            echo "<br/>";
            echo $this->Form->input('order_id', array(
                'type' => 'text',
                'placeholder' => 'Order ID.',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'OrderID'
                ),
            ));

            echo $this->Form->input('username', array(
                'type' => 'text',
                'placeholder' => 'username, name, email or slug.',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Username'
                ),
            ));

            echo "<br/>";
            echo $this->Form->submit('Search', array('class' => 'btn'));
            ?>
        </div>

        <div class='span3'>
            <?php

            echo $this->Form->input('cardnumber', array(
                'type' => 'text',
                'placeholder' => 'Nhập serial',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Serial thẻ'
                ),
            ));

            echo $this->Form->input('cardcode', array(
                'type' => 'text',
                'placeholder' => 'Nhập mã thẻ',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Mã thẻ'
                ),
            ));
            ?>
        </div>

        <div class='span3'>
            <?php
            echo $this->Form->input('type', array(
                'type' => 'select',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Loại thẻ'
                ),
                'empty' => '-- All Type Card --',
                'options' => array(
                    'VTT' => 'Viettel',
                    'VNP' => 'Vinaphone',
                    'VMS' => 'Mobifone'
                ),
            ));

            echo "<br/>";
            echo $this->Form->input('chanel', array(
                'type' => 'select',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Kênh thanh toán'
                ),
                'empty' => '-- All Chanel --',
                'options' => array(
                    '1' => 'Vippay'
                ),
            ));
            ?>
        </div>
        <?php  echo $this->Form->end(); ?>
    </div>
</div>

<br/>
<div class='row'>
<div class='span12'>
    <table class="table table-striped">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo 'Username'; ?></th>
            <th><?php echo 'Game'; ?></th>
            <th><?php echo $this->Paginator->sort('order_id'); ?></th>
            <th><?php echo $this->Paginator->sort('card_code'); ?></th>
            <th><?php echo $this->Paginator->sort('card_serial'); ?></th>
            <th><?php echo $this->Paginator->sort('price'); ?></th>
            <th><?php echo $this->Paginator->sort('time'); ?></th>
            <th><?php echo $this->Paginator->sort('type'); ?></th>
            <th><?php echo $this->Paginator->sort('chanel'); ?></th>
            <th><?php echo $this->Paginator->sort('note'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
        </tr>

        <?php foreach ($payments as $payment): ?>
            <?php
                $style = "";
                if($payment['Payment']['test']) $style = "color: red;"
            ?>
            <tr style="<?php echo $style; ?>">
                <td><?php echo h($payment['Payment']['id']); ?>&nbsp;</td>
                <td> <?php echo $this->Html->link($payment['User']['username'], array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?> </td>
                <td> <?php echo $payment['Game']['title'] . '_' . $payment['Game']['os']; ?> </td>
                <td> <?php echo $payment['Payment']['order_id']; ?> </td>
                <td> <?php echo $payment['Payment']['card_code']; ?> </td>
                <td> <?php echo $payment['Payment']['card_serial']; ?> </td>
                <td> <?php echo $payment['Payment']['price']; ?> </td>
                <td> <?php echo $payment['Payment']['time']; ?> </td>
                <td> <?php echo $payment['Payment']['type']; ?> </td>
                <td>
                    <?php
                    $chanel = '';
                    switch ($payment['Payment']['chanel']){
                        case Payment::CHANEL_VIPPAY :
                            $chanel = 'Vippay';
                            break;
                    }
                    echo $chanel;
                    ?>
                </td>
                <td> <?php echo $payment['Payment']['note']; ?> </td>
                <td> <?php echo $payment['Payment']['created']; ?> </td>
                <td> <?php echo $payment['Payment']['modified']; ?> </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
        ));
        ?>
    </p>
    <div class="paging">
        <?php
        echo $this->element('paging');
        ?>
    </div>
</div>
</div>

<script type="text/javascript" >
    var time_today = <?php echo strtotime('tomorrow midnight'); ?>;

    $(function() {
        $("#PaymentGameId").chosen();

//        $('#quick_datepicker').change(function() {
//            if ($(this).val()) {
//                var interval = $(this).val() * 24 * 3600;
//
//                if ($('#to_time').val() != time_today) {
//                    $('#to_time').val(time_today).trigger('change');
//                }
//
//                if ($('#from_time').val() != time_today - interval) {
//                    $('#from_time').val(time_today - interval).trigger('change');
//                }
//            }
//        });
//
//        // Set DateAPicker Js defaults
//        $('.date-range-picker .datepicker').each(function(i, e) {
//            var bindElement = $(e).data('bind');
//            var $input = $(e).pickadate({
//                onStart: function() {
//                    if ($("#" + bindElement).val() != '') {
//                        this.set('select', $("#" + bindElement).val() * 1000);
//                    }
//                },
//                onSet: function(thingSet) {
//                    if (thingSet.select !== undefined) {
//                        $("#" + bindElement).val(thingSet.select / 1000);
//                    } else {
//                        $("#" + bindElement).val("");
//                    }
//                }
//            });
//        });

    });
</script>