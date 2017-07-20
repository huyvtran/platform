<?php 
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Compense Payment Management </h3>
    </div>
</div>

<div class='row'>
    <?php
        echo $this->Form->create('CompensePayment', array(
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

    <div class="span8">
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
            <th><?php echo $this->Paginator->sort('card_code'); ?></th>
            <th><?php echo $this->Paginator->sort('card_serial'); ?></th>
            <th><?php echo $this->Paginator->sort('price'); ?></th>
            <th><?php echo $this->Paginator->sort('type'); ?></th>
            <th><?php echo $this->Paginator->sort('chanel'); ?></th>
            <th><?php echo $this->Paginator->sort('description'); ?></th>
            <th><?php echo $this->Paginator->sort('last_user'); ?></th>
            <th><?php echo $this->Paginator->sort('status'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th class="actions">Actions</th>
        </tr>

        <?php foreach ($compense as $comp): ?>
            <?php
                $style = "";
            ?>
            <tr style="<?php echo $style; ?>">
                <td><?php echo h($comp['CompensePayment']['id']); ?>&nbsp;</td>
                <td> <?php echo $this->Html->link($comp['User']['username'], array('controller' => 'users', 'action' => 'view', $comp['User']['id'])); ?> </td>
                <td> <?php echo $comp['Game']['title'] . ' ' . $comp['Game']['os']; ?> </td>
                <td> <?php echo $comp['CompensePayment']['order_id']; ?> </td>
                <td> <?php echo $comp['CompensePayment']['card_code']; ?> </td>
                <td> <?php echo $comp['CompensePayment']['card_serial']; ?> </td>
                <td> <?php echo number_format( $comp['CompensePayment']['price'], 0, '.', ','); ?> </td>
                <td> <?php echo $comp['CompensePayment']['type']; ?> </td>
                <td>
                    <?php
                    $chanel = '';
                    switch ( $comp['CompensePayment']['chanel'] ){
                        case Payment::CHANEL_VIPPAY :
                            $chanel = 'Vippay';
                            break;
                        case Payment::CHANEL_HANOIPAY :
                            $chanel = 'Hanoipay';
                            break;
                    }
                    echo $chanel;
                    ?>
                </td>
                <td> <?php echo $comp['CompensePayment']['description']; ?> </td>
                <td> <?php echo $comp['CompensePayment']['last_user']; ?> </td>
                <td> <?php
                    if ( empty($comp['CompensePayment']['status']) ) {
                        echo '<span class="label label-warning">Wait</span>';
                    }else{
                        echo '<span class="label label-success">OK</span>';
                    }
                    ?>
                </td>
                <td> <?php echo $comp['CompensePayment']['created']; ?> </td>
                <td> <?php echo $comp['CompensePayment']['modified']; ?> </td>
                <td class="actions btn-group">
                    <?php
                    if ( empty($comp['CompensePayment']['status']) ) {
                        echo $this->Html->link('compense', array('action' => 'compense', $comp['CompensePayment']['id']), array('class' => 'btn btn-mini'));
                        echo $this->Html->link('Edit', array('action' => 'edit', $comp['CompensePayment']['id']), array('class' => 'btn btn-mini'));
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

    <div class="actions">
        <h3><?php echo 'Actions'; ?></h3>
        <ul>
            <li><?php echo $this->Html->link('New Compense', array('action' => 'add')); ?></li>
        </ul>
    </div>
</div>
</div>

<script type="text/javascript" >
    var time_today = <?php echo strtotime('tomorrow midnight'); ?>;

    $(function() {
        $("#CompensePaymentGameId").chosen();

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