<?php $this->extend('/Common/blank'); ?>
<h2>Redis detail</h2>
<table cellpadding="0" cellspacing="0" class="table">
    <thead>
    <tr>
        <th><?php echo 'std';?></th>
        <th><?php echo 'description';?></th>
    </tr>
    </thead>

    <tbody>
    <?php $i = 0;?>
    <?php foreach ($data as $result): ?>
        <tr>
            <td> <?php $i++; echo $i; ?> </td>
            <td>
                <?php
                if(is_array($result)) echo serialize($result);
                else echo $result;
                ?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>