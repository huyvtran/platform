<style>
    .name1 {
        width: 230px;
    }
    .data {
        width: 90px;
    }
    .total_data {
        width: 135px;
    }
</style>
<?php
if (!$this->request->is('ajax')) {
    echo $this->extend('/Common/fluid');
    ?>
    <div class='row'>
        <div class="col-md-11 offset1">
            <div>
                <?php
                echo $this->Form->create('LogLoginsByDay', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
                echo '<div class="form-group">';

                echo $this->Form->input('game_id', array(
                    'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
                    'value' => empty($this->request->params['named']['game_id']) ? '': $this->request->params['named']['game_id']
                ));
                echo $this->element('date_ranger_picker');
                echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));

                echo '</div>';
                echo $this->Form->end()
                ?>
            </div>
        </div>
    </div>
    <?php
}
if (empty($data)) {
    goto a;
}
?>
<div class='row'>
    <div class='md-col-12' >
        <table class='table table-striped table-bordered table-data responsive'>
            <thead>
            <th>Games</th>
            <?php
            for($i=0 ;$i < count($rangeDates); $i++){
                echo "<th>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
            }
            ?>
            <th class="int">AVG</th>
            <th class='int'>In Range</th>
            </thead>
            <tbody>
            <?php

            # Calculate totals
            $totals = array();
            foreach($data as $v) {
                foreach($v['data'] as $kk => $count) {
                    if (isset($totals[$kk])) {
                        $totals[$kk] += $count;
                    } else {
                        $totals[$kk] = $count;
                    }
                }
            }

            # print data to table
            echo '<tr>';
            echo '<td class="total">Total</td>';
            foreach($totals as $val) {
                echo '<td class="total int">' . n($val) . '</td>';
            }
            echo '<td class="total int">' . n(array_sum($totals) / count($rangeDates)) . '</td>';
            echo '<td class="total int">' . n(array_sum($totals)) . '</td>';
            echo '</tr>';

            foreach($data as $v) {
                $range = 0;
                echo '<tr>';

                echo '<td class="name1">' . $v['name'] . '</td>';
                foreach($v['data'] as $kk => $count) {
                    $range += $count;
                    echo '<td class="int data">' . n($count) . '</td>';
                }
                $class = '';
                $rate = '';
                if (isset($total) && !empty($total)) {
                    foreach ($total as $value) {
                        if ($value['game_id'] == $v['game_id']) {
                            if ($range > $value['sum']) {
                                $rate = round((abs($range - $value['sum']) / $range) * 100, 1) . '%';
                                $class = 'glyphicon glyphicon-arrow-up';
                            } else if ($range < $value['sum']) {
                                $rate = round((abs($range - $value['sum']) / $range) * 100, 1) . '%';
                                $class = 'glyphicon glyphicon-arrow-down';
                            } else if ($range == $value['sum']) {
                                $rate = '&nbsp;<span title="no change">0%</span>';
                            }
                            if ($rate > 10000) $rate = '<b>&infin;</b>';
                        }
                    }
                }
                $a = n($range / count($rangeDates));
                switch (strlen($a)) {
                    case 1 :
                        $a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
                        break;
                    case 2 :
                        $a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
                        break;
                    case 3 :
                        $a = $a . "&nbsp;&nbsp;&nbsp;&nbsp; ";
                        break;
                    case 4 :
                        $a = $a . "&nbsp;&nbsp;&nbsp;  ";
                        break;
                    case 5 :
                        $a = $a . "&nbsp; ";
                        break;
                }
                ?>
                <td class="int total total_data"><?php echo $a;?><?php echo ($class != '') ? '<label class="' . $class . '"></label>' : '';?><?php echo ($rate != '') ? $rate : '&nbsp;<span title="no data">--</span>';?></td>
                <?php
                echo '<td class="total int">' . n($range) . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php
a: