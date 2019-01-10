<?php 
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Manual Management </h3>
    </div>
</div>

<div class='row'>
    <?php
        echo $this->Form->create('BankManual', array(
            'url' => array('controller' => 'BankManual', 'action' => 'index'),
            'class' => 'form-horizontal',
            'inputDefaults' => array(
                'label' => array(
                    'class' => 'control-label'
                ),
                'div' => 'control-group',
                'between' => '<div class="controls">',
                'after' => '</div>',
            )
        ));
    ?>
    <div class="span4">
        <?php
        echo $this->Form->input('username', array(
            'type' => 'text',
            'placeholder' => 'id, username or email.',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Username'
            ),
        ));

        echo $this->Form->input('order_id', array(
            'type' => 'text',
            'placeholder' => 'Order ID.',
            'label' => array(
                'class' => 'control-label',
                'text' => 'OrderID'
            ),
        ));
        ?>
    </div>

    <div class="span4">
        <?php
        echo $this->Form->input('buyer_name', array(
            'type' => 'text',
            'placeholder' => 'Full Name',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Full Name'
            ),
        ));

        echo $this->Form->input('buyer_phone', array(
            'type' => 'text',
            'placeholder' => 'Nhập phone',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Phone'
            ),
        ));

        echo $this->Form->input('buyer_email', array(
            'type' => 'text',
            'placeholder' => 'Nhập email',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Email'
            ),
        ));
        ?>
    </div>

    <div class="span4">
        <?php
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

        echo $this->Form->input('status', array(
            'type' => 'select',
            'label' => array(
                'class' => 'control-label',
                'text' => 'status'
            ),
            'empty' => 'All status',
            'options' => $status,
            'selected' => !empty($this->request->params['named']['status']) ? $this->request->params['named']['status'] : ''
        )); echo "<br/>";
        ?>
    </div>

    <div class="span12">
        <div class="control-group">
            <label class="control-label" >
                Time
            </label>
            <div class="controls date-range-picker">
                <?php
                echo $this->Form->input('from_time', array(
                    'id' => 'from_time',
                    'value' => isset($this->request->params['named']['from_time']) ? $this->request->params['named']['from_time'] : '',
                    'type' => 'hidden',
                ));
                ?>
                <input class="datepicker" type='text' placeholder="Begin time" data-bind='from_time'>
                <span >&nbsp;To&nbsp;</span>
                <?php
                echo $this->Form->input('to_time', array(
                    'id' => 'to_time',
                    'value' => isset($this->request->params['named']['to_time']) ? $this->request->params['named']['to_time'] : '',
                    'type' => 'hidden',
                ));
                ?>
                <input class="datepicker" type='text' placeholder="End time" data-bind='to_time'>
            </div>
        </div>

        <div class="form-actions">
            <?php
            echo $this->Form->button('Search', array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'div' => false
            ));
            echo '  ';
            echo $this->Form->submit('Clear', array(
                'class' => 'btn',
                'name' => 'submit',
                'div' => false
            ));
            ?>
        </div>
    </div>
    <?php  echo $this->Form->end(); ?>
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
            <th><?php echo $this->Paginator->sort('buyer_name'); ?></th>
            <th><?php echo $this->Paginator->sort('buyer_phone'); ?></th>
            <th><?php echo $this->Paginator->sort('buyer_email'); ?></th>
            <th><?php echo $this->Paginator->sort('price'); ?></th>
            <th><?php echo $this->Paginator->sort('detail', 'Note'); ?></th>
            <th><?php echo $this->Paginator->sort('status'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th class="actions">Actions</th>
        </tr>

        <?php foreach ($orders as $payment): ?>
            <?php
                $style = "";
            ?>
            <tr style="<?php echo $style; ?>">
                <td><?php echo h($payment['BankManual']['id']); ?>&nbsp;</td>
                <td> <?php echo $this->Html->link(substr($payment['User']['username'], 4), array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?> </td>
                <td> <?php echo substr($payment['Game']['title'], 5) . ' ' . $payment['Game']['os']; ?> </td>
                <td> <?php echo $payment['BankManual']['order_id']; ?> </td>
                <td> <?php echo $payment['BankManual']['buyer_name']; ?> </td>
                <td> <?php echo $payment['BankManual']['buyer_phone']; ?> </td>
                <td> <?php echo $payment['BankManual']['buyer_email']; ?> </td>
                <?php
                $card_price = 0;
                if( !empty($payment['BankManual']['price']) ) $card_price = $payment['BankManual']['price'];
                ?>
                <td> <?php echo number_format($card_price, 0, '.', ','); ?> </td>
                <td> <?php if( !empty($payment['BankManual']['detail']) ) echo $payment['BankManual']['detail']; ?> </td>
                <td> <?php
                    $status = '';
                    if ( isset($payment['BankManual']['status']) ) {
                        switch ($payment['BankManual']['status']){
                            case WaitingPayment::STATUS_WAIT:
                                $status = '<span class="label label-default">Create</span>';
                                break;
                            case WaitingPayment::STATUS_COMPLETED:
                                $status = '<span class="label label-success">OK</span>';
                                break;
                            case WaitingPayment::STATUS_ERROR:
                                $status = '<span class="label label-important">Error</span>';
                                break;
                            case WaitingPayment::STATUS_REVIEW:
                                $status = '<span class="label label-warning">Review</span>';
                                break;
                        }

                        echo $status;
                    }
                ?> </td>

                <td> <?php echo $payment['BankManual']['created']; ?> </td>
                <td> <?php echo $payment['BankManual']['modified']; ?> </td>
                <td class="actions btn-group">
                    <?php
                    if ( empty($payment['BankManual']['status']) ) {
                        echo $this->Html->link('Edit', array('action' => 'edit', $payment['BankManual']['id']), array('class' => 'btn btn-mini'));
                    }
                    if ( !empty($payment['BankManual']['status']) && $payment['BankManual']['status'] == WaitingPayment::STATUS_REVIEW) {
                        echo $this->Html->link('Edit', array('action' => 'edit', $payment['BankManual']['id']), array('class' => 'btn btn-mini'));
                        echo $this->Html->link('Publish', array('action' => 'publish', $payment['BankManual']['id']), array('class' => 'btn btn-mini'));
                    }
                    ?>
                </td>
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
        $("#BankManualGameId").chosen();

        $('#quick_datepicker').change(function() {
            if ($(this).val()) {
                var interval = $(this).val() * 24 * 3600;

                if ($('#to_time').val() != time_today) {
                    $('#to_time').val(time_today).trigger('change');
                }

                if ($('#from_time').val() != time_today - interval) {
                    $('#from_time').val(time_today - interval).trigger('change');
                }
            }
        });

        // Set DateAPicker Js defaults
        $('.date-range-picker .datepicker').each(function(i, e) {
            var bindElement = $(e).data('bind');
            var $input = $(e).pickadate({
                onStart: function() {
                    if ($("#" + bindElement).val() != '') {
                        this.set('select', $("#" + bindElement).val() * 1000);
                    }
                },
                onSet: function(thingSet) {
                    if (thingSet.select !== undefined) {
                        $("#" + bindElement).val(thingSet.select / 1000);
                    } else {
                        $("#" + bindElement).val("");
                    }
                }
            });
        });

    });
</script>