<?php
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Management </h3>
    </div>
</div>

<div class='row'>
    <?php
        echo $this->Form->create('Payment', array(
            'action' => 'index',
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
	if (!empty($this->data['Bonus']['id'])) {
		echo $this->Form->input('id');
	}
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
            'placeholder' => 'id, username or email.',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Username'
            ),
        ));
        ?>
    </div>

    <div class="span3">
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

    <div class="span4">
        <?php
        echo $this->Form->input('type', array(
            'type' => 'select',
            'label' => array(
                'class' => 'control-label',
                'text' => 'Loại thẻ'
            ),
            'empty' => '-- All Type Card --',
            'options' => $types,
        ));

        echo $this->Form->input('chanel', array(
            'type' => 'select',
            'label' => array(
                'class' => 'control-label',
                'text' => 'chanel'
            ),
            'empty' => 'All chanel',
            'options' => $chanels,
            'selected' => !empty($this->request->params['named']['chanel']) ? $this->request->params['named']['chanel'] : ''
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
<!--                <select name="quick_datepicker" id="quick_datepicker" >-->
<!--                    <option value="">-- Custom --</option>-->
<!--                    <option value="0">Today</option>-->
<!--                    <option value="2">Last 3 days</option>-->
<!--                    <option value="6">Within a week</option>-->
<!--                    <option value="29">Within a month</option>-->
<!--                    <option value="91">Within 3 months</option>-->
<!--                    <option value="364">Within a year</option>-->
<!--                </select>-->
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
                <td> <?php echo $payment['Game']['title'] . ' ' . $payment['Game']['os']; ?> </td>
                <td> <?php echo $payment['Payment']['order_id']; ?> </td>
                <td> <?php echo $payment['Payment']['card_code']; ?> </td>
                <td> <?php echo $payment['Payment']['card_serial']; ?> </td>
                <td> <?php echo number_format($payment['Payment']['price'], 0, '.', ','); ?> </td>
                <td> <?php echo $payment['Payment']['time']; ?> </td>
                <td> <?php echo $payment['Payment']['type']; ?> </td>
                <td>
                    <?php
                    $chanel = '';
                    switch ($payment['Payment']['chanel']){
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
