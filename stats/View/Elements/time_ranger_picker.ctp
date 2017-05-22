<span class='date-ranger-picker'>
    <?php
    echo $this->Form->input('time_start', array(
        'type' => 'hidden',
        'id' => 'time_start',
        'value' => isset($time_start) ? $time_start : '',
        'class' => 'span1'
    ));
    echo $this->Form->input('fromTime', array(
        'type' => 'hidden', 'id' => 'fromTime', 'value' => isset($fromTime) ? $fromTime : '',
        'class' => 'span1'
    ));
    ?>
    <input class="timepicker form-control" type='text' placeholder="Begin time" data-bind='time_start'>
    <input class="datepicker form-control" type='text' placeholder="Begin date" data-bind='fromTime'>
    <span>To</span>
    <?php
    echo $this->Form->input('time_end', array(
        'type' => 'hidden',
        'id' => 'time_end',
        'value' => isset($time_end) ? $time_end : '',
        'class' => 'span1'
    ));
    echo $this->Form->input('toTime', array(
        'type' => 'hidden', 'id' => 'toTime', 'value' => isset($toTime) ? $toTime : '',
        'class' => 'span1'
    ));
    ?>
    <input class="timepicker form-control time" type='text' placeholder="End time" data-bind='time_end'>
    <input class="datepicker form-control" type='text' placeholder="End date" data-bind='toTime'>
</span>
<script type='text/javascript'>
    // Set DateAPicker Js defaults
    $('.datepicker').each(function(i, e) {
        var bindElement = $(e).data('bind');
        var now_date = new Date();
        now_date.setDate(now_date.getDate()-1);
        var $input = $(e).pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
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
        var picker = $input.pickadate('picker');
    });
    $('.timepicker').each(function(i, e) {
        var bindElement = $(e).data('bind');
        var $input = $(e).pickatime({
            format: 'HH:i',
            formatSubmit: 'HH:i',
            interval: 1,
            onStart: function() {
                var time = $("#" + bindElement).val();
                if (time != '') {
                    this.set('select', time / 1000);
                }
            },
            onSet: function() {
                var time = this.get('select');
                $("#" + bindElement).val(time.pick * 1000);
            }
        });
        var picker = $input.pickatime('picker');
    });
</script>