<?php
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment ReCharges Management </h3>
    </div>
</div>

<div class="row">
    <?php
    echo $this->Form->create('Charge', array(
        'action' => 'index',
        'class' => 'form-horizontal',
        'inputDefaults' => array(
            'div' => 'form-group',
            'class' => 'form-control'
        )
    ));
    ?>
    <div class="span12">
		<div class="span4">
			<?php
				echo $this->Form->input('game_id.', array(
					'type' => 'select',
					'label' => array(
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
					'text' => 'Username'
				),
			));
			?>
		</div>
	

        <div class="span8">
			<div class="form-group">
				<label>
					Time
				</label>
				<div class="date-range-picker">
					<?php
					echo $this->Form->input('from_time', array(
						'id' => 'from_time',
						'value' => isset($this->request->params['named']['from_time']) ? $this->request->params['named']['from_time'] : '',
						'type' => 'hidden'
					));
					?>
					<input class="datepicker" type='text' placeholder="Begin time" class="form-control" data-bind='from_time'>
					<span >&nbsp;To&nbsp;</span>
					<?php
					echo $this->Form->input('to_time', array(
						'id' => 'to_time',
						'value' => isset($this->request->params['named']['to_time']) ? $this->request->params['named']['to_time'] : '',
						'type' => 'hidden',
					));
					?>
					<input class="datepicker" type='text' placeholder="End time" class="form-control" data-bind='to_time'>
				</div>
			</div>
		</div>
		
		<div class="span4">
			<?php
			echo $this->Form->input('order_id', array(
				'type' => 'text',
				'placeholder' => 'Order ID.',
				'label' => array(
					'text' => 'OrderID'
				),
			));
			?>
		</div>
		
		<div class="span12"><br/>
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
                    <th><?php echo $this->Paginator->sort('time'); ?></th>
                    <th><?php echo $this->Paginator->sort('note'); ?></th>
                    <th><?php echo $this->Paginator->sort('status'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo $this->Paginator->sort('modified'); ?></th>
                </tr>

                <?php foreach ($orders as $payment): ?>
                    <?php
                    $style = "";
                    if( !empty($payment['Charge']['test']) ) $style = "color: red;"
                    ?>
                    <tr style="<?php echo $style; ?>">
                        <td><?php echo h($payment['Charge']['id']); ?>&nbsp;</td>
                        <td> <?php echo $this->Html->link($payment['User']['username'], array('controller' => 'users', 'action' => 'view', $payment['User']['id'])); ?> </td>
                        <td> <?php echo $payment['Game']['title'] . ' ' . $payment['Game']['os']; ?> </td>
                        <td> <?php echo $payment['Charge']['order_id']; ?> </td>
                        <td> <?php if( !empty($payment['Charge']['price']) ) echo number_format($payment['Charge']['price'], 0, '.', ','); ?> </td>
                        <td> <?php echo $payment['Charge']['time']; ?> </td>
                        <td> <?php if( !empty($payment['Charge']['note']) ) echo $payment['Charge']['note']; ?> </td>
                        <td> <?php
                            $status = '';
                            if ( isset($payment['Charge']['status']) ) {
                                switch ($payment['Charge']['status']){
                                    case Charge::STATUS_WAIT:
                                        $status = '<span class="label label-default">Unverified</span>';
                                        break;
                                    case Charge::STATUS_COMPLETED:
                                        $status = '<span class="label label-success">OK</span>';
                                        break;
                                    case Charge::STATUS_ERROR:
                                        $status = '<span class="label label-important">Error</span>';
                                        break;
                                }

                                echo '<span class="label label-success">OK</span>';
                            }
                            ?> </td>

                        <td> <?php echo $payment['Charge']['created']; ?> </td>
                        <td> <?php echo $payment['Charge']['modified']; ?> </td>
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
        $("#ChargeGameId").chosen();

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