<style>
	.glyphicon-arrow-down {
		color:orange;
	}
	.glyphicon-arrow-up {
		color:green;
	}
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

<div class='row-fluid'>
	<div class="span11 offset1">
		<div>
			<?php
			echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo $this->Form->input('alias',
                    array('empty' => '--All Games--', 'options' => $games,
                        'value' => empty($this->request->params['named']['alias']) ? '': $this->request->params['named']['alias']
                    )) . ' ';
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			echo $this->Form->end()
			?>
		</div>
	</div>
</div>

<br/><br/>
<div class='row'>
    <div class='md-col-12' >
        <?php if(!empty($results)){?>

        <table class='table table-striped table-bordered table-data responsive table_cus'>
            <thead>
                <tr>
                    <td> Vip </td>
                    <td> Count </td>
                    <td> <?php echo empty($this->request->params['named']['alias']) ? 'vip all game': $games[$this->request->params['named']['alias']] ?> </td>
                    <td width="10px"> Range = <?php echo empty($this->request->params['named']['alias']) ? '(vip all game)': '(User vip ' .$games[$this->request->params['named']['alias']] . ')' ?> / (Total User Funtap)</td>
                </tr>
            </thead>
            <tbody>

            <?php
            # Calculate totals
            $totals = array();
            foreach($results as $v) {
                foreach($v[0] as $kk => $count) {
                    if (isset($totals[$kk])) {
                        $totals[$kk] += $count;
                    } else {
                        $totals[$kk] = $count;
                    }
                }
            }
            ?>
            <tr class="selected-total">
                <td class="total"><?php echo 'Total' ?></td>
                <td class="total"><?php echo $totals['count'] ?></td>
                <td class="total"><?php echo $totals['count'] ?></td>
                <td class="total"><?php echo round($totals['count']/$totalUserFuntap*100 , 3) .'%'; ?></td>
            </tr>

            <?php foreach( $results as $result){ ?>
            <tr class="selected-total">
                <td><?php echo $result['User']['vip'] ?></td>
                <td><?php echo $result[0]['count'] ?></td>
                <td><?php echo $totals['count'] ?></td>
                <td><?php echo round($result[0]['count']/$totalUserFuntap*100 , 3) .'%'; ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>
</div>