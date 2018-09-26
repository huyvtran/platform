<?php $this->extend('/Common/blank'); ?>
<h2>Redis detail</h2>
<table cellpadding="0" cellspacing="0" class="table">
    <thead>
    <tr>
        <th><?php echo 'std';?></th>
        <th><?php echo 'description';?></th>
        <th> Action</th>
    </tr>
    </thead>

    <tbody>
    <?php $i = 0;?>
    <?php foreach ($results as $result): ?>
        <tr>
            <td> <?php $i++; echo $i; ?> </td>
            <td>
                <?php
                if(is_array($result)) echo serialize($result);
                else echo $result;
                ?>
            </td>
            <td>
                <?php

                $link = $this->Html->url(array(
                    'controller' => 'Administrators',
                    'action' => 'redis_detail_delete',
                    'server' => $this->request->params['named']['server'],
                    'key' => $this->request->params['named']['key'],
                    'type' => $this->request->params['named']['type'],
                    'value' => $result
                ));
                ?>
                <a href="<?php echo $link ?>"> Xóa</a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>