<?php
echo $this->Html->css('/js/jquery-ui-1.10.3/css/redmond/jquery-ui-1.10.3.custom.min.css', false, array('inline' => false));
echo $this->Html->script('jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.min.js', array('inline' => false));
?>
<style type="text/css">
	.ui-datepicker-calendar{display: none}
	.ui-datepicker-month, .ui-datepicker-year {color: #333;}
</style>
<span class='date-ranger-picker'>
	<i class='icon-calendar'></i>
	<?php
	echo $this->Form->input('fromTime', array(
		'type' => 'text', 'id' => 'from', 'value' => isset($fromTime) ? date('m-Y', $fromTime) : '',
		'class' => 'form-control picker__input', 'readonly' => 'readonly'
		));
	?>
	<span class='muted textto'>To </span>
	<?php
	echo $this->Form->input('toTime', array(
		'type' => 'text', 'id' => 'to', 'value' => isset($toTime) ? date('m-Y', $toTime) : '',
		'class' => 'form-control picker__input', 'readonly' => 'readonly'
		));
	?>
</span>

<script type='text/javascript'>
	$(function() {
	    $("#from, #to").datepicker({
	        changeMonth: true,
	        changeYear: true,
	        showButtonPanel: true,
	        dateFormat: 'mm-yy',
	        onClose: function(dateText, inst) {
	            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
	            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
	            $(this).datepicker('setDate', new Date(year, month, 1));
	        },
	        beforeShow: function(input, inst) {
	            if ((datestr = $(this).val()).length > 0) {
	                year = datestr.substring(datestr.length - 4, datestr.length);
	                month = datestr.substring(0, datestr.length - 5);
	                $(this).datepicker('option', 'defaultDate', new Date(year, month - 1, 1));
	            }
	            // temp vars used below
	            var other = this.id == "from" ? "#to" : "#from";
	            var option = this.id == "from" ? 'maxDate' : 'minDate';
	            if ((selectedDate = $(other).val()).length > 0) {
	                year = selectedDate.substring(selectedDate.length - 4, selectedDate.length);
	                month = selectedDate.substring(0, selectedDate.length - 5);
	                $(this).datepicker("option", option, new Date(year, month, 1));
	            }
	        }
	    });		
	})
</script>