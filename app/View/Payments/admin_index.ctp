<?php 
$this->extend('/Common/blank');
?>

<div class="row">
    <div class="span12">
        <h3 class="page-header"> Payment Management </h3>
    </div>
</div>

<div class='row'>
    <div class='span12'>
        <div class='span3'>
            <?php echo $this->Form->create('Payment', array('action' => 'index', 'class' => 'simple', 'style' => 'width:200px;')); ?>
            <?php echo $this->Form->input('id', array(
                'empty' => "All Games",
                'options' => $games,
            ));
            echo "<br/>";
            echo $this->Form->submit('Search', array('class' => 'btn'));
            ?>
        </div>
        <div class='span3'>
            <?php echo $this->Form->input('account_id', array('type' => 'text', 'label' => 'Account ID')); ?>
        </div>
        <?php  echo $this->Form->end(); ?>
    </div>
</div>

<br/>
<div class='row'>
<div class='span12'>
    <table class="table table-striped">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo 'Username'; ?></th>
            <th><?php echo 'Game'; ?></th>
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
                <td> <?php echo $payment['Game']['title'] . '_' . $payment['Game']['os']; ?> </td>
                <td> <?php echo $payment['Payment']['card_code']; ?> </td>
                <td> <?php echo $payment['Payment']['card_serial']; ?> </td>
                <td> <?php echo $payment['Payment']['price']; ?> </td>
                <td> <?php echo $payment['Payment']['time']; ?> </td>
                <td> <?php echo $payment['Payment']['type']; ?> </td>
                <td>
                    <?php
                    $chanel = '';
                    switch ($payment['Payment']['chanel']){
                        case Payment::CHANEL_VIPPAY :
                            $chanel = 'Vippay';
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

