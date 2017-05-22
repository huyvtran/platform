<?php
$currentY = date('Y', strtotime('today'));
for($i = 2013; $i <= (int)$currentY; $i++){
    $years[$i] = $i;
}

echo $this->extend('/Common/fluid');
?>
    <div class='row-fluid'>
        <div class="span11 offset1">
            <div>
                <?php
                echo $this->Form->create('LogLoginsByQuarter', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
                echo $this->Form->input('game_id', array('empty' => '--All Games--', 'options' => $games)) . ' ';
                //echo $this->Form->input('Y', array('div' => false, 'multiple' => 'checkbox', 'options' => $years));
                echo $this->Form->input('Y', array('empty' => '--Select Year--', 'options' => $years)) . ' ';
                echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));

                echo $this->Form->end()
                ?>
            </div>
        </div>
    </div>
<?php
if (empty($data)) {
    goto end_of_file;
}
?>
    <div id='chart'></div>
<?php
$i = 1;
foreach ($rangeDates as $q){
    $quarterNumber[] = 'Q'.$i++;
}

$this->Highchart->render(array(
    'title' => array('text' => 'Quarter Active Users'),
    'xAxis' => array(
        'title' => array('text' => 'Quarters',),
        'categories' => $quarterNumber,
    ),

    'yAxis' => array('title' => array('text' => 'Active users')),
), $data);
?>

    <!-- DATA TABLE -->
    <div class='row'>
        <div class='md-col-12' style='overflow:auto'>
            <table class='table table-striped table-bordered table-data'>
                <thead>

                <!-- HEADER ROW -->
                <th>Quarter</th>
                <?php $q= 1; foreach ($rangeDates as $date) : ?>
                    <th class="int">
                        <?php echo 'Q'.$q++; ?>
                    </th>
                <?php endforeach; ?>
                <th class="int">AVG</th>
                <th class="int">In Range</th>

                </thead>
                <tbody>

                <?php
                //print_r($data);
                # Calculate totals
                $totals = array();
                foreach ($data as $d) {
                    foreach ($d['data'] as $i => $value) {
                        if (isset($totals[$i])) {
                            $totals[$i] += $value;
                        } else {
                            $totals[$i] = $value;
                        }
                    }
                }
                ?>

                <!-- TOTAL ROW -->
                <tr>
                    <td class="total">All Games</td>
                    <?php foreach ($totals as $total) : ?>
                        <td class="total int"><?php echo n($total); ?></td>
                    <?php endforeach; ?>
                    <td class="total int"><?php echo n(array_sum($totals) / count($rangeDates)); ?></td>
                    <td class="total int"><?php echo n(array_sum($totals)); ?></td>
                </tr>

                <!-- OTHER ROWS -->
                <?php foreach ($data as $d) : ?>
                    <tr>
                        <?php $total_inrange = 0; ?>
                        <td class="name"><?php echo $d['name']; ?></td>
                        <?php foreach ($d['data'] as $i => $value) : $total_inrange += $value; ?>
                            <td class="int"><?php echo n($value); ?></td>
                        <?php endforeach; ?>
                        <td class="int total"><?php echo n($total_inrange / count($rangeDates)); ?></td>
                        <td class="int total"><?php echo n($total_inrange); ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
<?php
end_of_file: