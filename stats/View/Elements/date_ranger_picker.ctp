<span class='date-ranger-picker'>
	<i class='icon-calendar'></i>
	<?php
	echo $this->Form->input('fromTime', array(
		'type' => 'hidden', 'id' => 'fromTime', 'value' => isset($fromTime) ? $fromTime : '',
		'class' => 'span1'
		));
	?>
	<input class="datepicker form-control span2" type='text' placeholder="Begin time" data-bind='fromTime'>
	<span class='muted textto'>To </span>
	<?php
	echo $this->Form->input('toTime', array(
		'type' => 'hidden', 'id' => 'toTime', 'value' => isset($toTime) ? $toTime : '',
		'class' => 'span1'
		));
	?>
	<input class="datepicker form-control" type='text' placeholder="End time" data-bind='toTime'>
</span>
<script type='text/javascript'>
	// Set DateAPicker Js defaults
	$('.date-ranger-picker .datepicker').each(function(i, e) {
		var bindElement = $(e).data('bind');
		var $input = $(e).pickadate({
			onStart: function() {
				if ($("#" + bindElement).val() != '') {
					this.set('select', $("#" + bindElement).val() * 1000);
				}
			},
			onSet: function() {
				var date = this.get('select');
				$("#" + bindElement).val(date.pick / 1000);
			}
		});
		var picker = $input.pickadate('picker')
		picker.set('max', true);
	})
</script>