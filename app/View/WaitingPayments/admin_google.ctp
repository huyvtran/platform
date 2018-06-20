<?php 
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Google Inapp Management </h3>
    </div>
</div>

<div class='row'>
    <?php
        echo $this->Form->create('GoogleInappOrder', array(
            'url' => array('controller' => 'WaitingPayments', 'action' => 'google'),
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
        echo $this->Form->input('google_order_id', array(
            'type' => 'text',
            'placeholder' => 'Google Order ID.',
            'label' => array(
                'class' => 'control-label',
                'text' => 'OrderID'
            ),
        ));

        echo $this->Form->input('ip', array(
            'type' => 'text',
            'placeholder' => 'Ip',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Ip'
            ),
        ));
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
            <th><?php echo $this->Paginator->sort('google_order_id', 'google id'); ?></th>
            <th><?php echo 'Product'; ?></th>
            <th><?php echo $this->Paginator->sort('ip'); ?></th>
            <th><?php echo $this->Paginator->sort('device_id'); ?></th>
            <th><?php echo $this->Paginator->sort('status'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th class="actions">Actions</th>
        </tr>

        <?php foreach ($orders as $payment): ?>
            <tr>
                <td><?php echo h($payment['GoogleInappOrder']['id']); ?>&nbsp;</td>
                <td> <?php echo $this->Html->link(substr($payment['User']['username'], 4), array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?> </td>
                <td> <?php echo $payment['Game']['title'] . ' ' . $payment['Game']['os']; ?> </td>
                <td> <?php echo $payment['GoogleInappOrder']['order_id']; ?> </td>
                <td> <?php echo $payment['GoogleInappOrder']['google_order_id']; ?> </td>
                <td> <?php echo $payment['Product']['title']; ?> </td>
                <td> <?php echo $payment['GoogleInappOrder']['ip']; ?> </td>
                <td> <?php if(!empty($payment['GoogleInappOrder']['device_id'])) echo $payment['GoogleInappOrder']['device_id'] ; ?> </td>
                <td>
                    <?php
                    $status = '<span class="label label-success">OK</span>';
                    if ( !empty($payment['GoogleInappOrder']['status']) ) {
                        $status = '<span class="label label-important">Refun</span>';
                    }
                    echo $status;
                    ?>
                <td> <?php echo $payment['GoogleInappOrder']['created']; ?> </td>
                <td class="actions btn-group">
                    <?php
                        if( empty($payment['GoogleInappOrder']['status']) )
                        echo $this->Html->link('Block', array('action' => 'block', $payment['GoogleInappOrder']['order_id']), array('class' => 'btn btn-mini'));
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
        $("#GoogleInappOrderGameId").chosen();

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