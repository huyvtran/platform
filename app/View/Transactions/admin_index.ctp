<?php
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Transaction Management </h3>
    </div>
</div>

<div>
    <div class='row'>
        <?php
        echo $this->Form->create('Transaction', array(
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
        <div class="span12">
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

            echo $this->Form->input('username', array(
                'type' => 'text',
                'placeholder' => 'id, username or email.',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Username'
                ),
            ));
            ?>
            </div>

            <div class="span4">
                <?php
                echo $this->Form->input('type', array(
                    'type' => 'select',
                    'empty' => 'All Type',
                    'label' => array(
                        'class' => 'control-label',
                        'text' => 'Type'
                    ),
                    'options' => array(
                        Transaction::TYPE_PAY => 'Charge',
                        Transaction::TYPE_SPEND => 'Spend'
                    ),
                    'selected' => !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : ''
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
                ?>
            </div>
        </div>
        <?php  echo $this->Form->end(); ?>
    </div>
</div>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Data</h3>
        <div class="box-tools">
            <?php echo $this->element('paging'); ?>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                    <th><?php echo 'Username'; ?></th>
                    <th><?php echo 'Game'; ?></th>
                    <th><?php echo $this->Paginator->sort('order_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('price'); ?></th>
                    <th><?php echo $this->Paginator->sort('type'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo $this->Paginator->sort('modified'); ?></th>
                </tr>

                <?php foreach ($orders as $payment): ?>
                    <tr>
                        <td><?php echo h($payment['Transaction']['id']); ?>&nbsp;</td>
                        <td> <?php echo $this->Html->link(substr($payment['User']['username'], 4), array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?> </td>
                        <td> <?php if(!empty($this->request->params['named']['game_id'])) echo $payment['Transaction']['game_id'];
                            else echo $payment['Game']['title'] . ' ' . $payment['Game']['os'];
                            ?>
                        </td>
                        <td> <?php echo $payment['Transaction']['order_id']; ?> </td>
                        <td> <?php if( !empty($payment['Transaction']['price']) ) echo number_format($payment['Transaction']['price'], 0, '.', ','); ?> </td>
                        <td> <?php
                            $status = '';
                            if ( !empty($payment['Transaction']['type']) ) {
                                switch ($payment['Transaction']['type']){
                                    case Transaction::TYPE_PAY:
                                        $status = '<span class="label label-success">Chagre</span>';
                                        break;
                                    case Transaction::TYPE_SPEND:
                                        $status = '<span class="label label-important">Spend</span>';
                                        break;
                                }
                                echo $status;
                            }
                            ?> </td>

                        <td> <?php echo $payment['Transaction']['created']; ?> </td>
                        <td> <?php echo $payment['Transaction']['modified']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
            ));
            ?>
        </p>
    </div>
    <div class="box-footer clearfix">
        <?php echo $this->element('paging'); ?>
    </div>
</div>

<script type="text/javascript" >
    var time_today = <?php echo strtotime('tomorrow midnight'); ?>;

    $(function() {
        $("#TransactionGameId").chosen();

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