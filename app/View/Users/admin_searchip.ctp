<?php
$this->extend('/Common/blank');
App::import('Lib', 'RedisCake');
$Redis = new RedisCake('action_count');
?>
<div class="users index">
    <h2>Users</h2>
    <div class='span3'>
        <?php
        echo $this->Form->create('LogLogin', array(
            'url' => array('controller' => 'Users', 'action' => 'searchip'),
        ));
        echo $this->Form->input('ip', array('type' => 'text','required' => false));
        echo $this->Form->submit('Search', array('class' => 'btn'));
        echo $this->Form->end();
        ?>
    </div>
    <?php echo $this->element('paging'); ?>
    <table class = "table">
        <tr>
            <th><small><?php echo $this->Paginator->sort('id'); ?></small></th>
            <th><small>Username</small></th>
            <th><small>Game</small></th>
            <th><small>Active</small></th>
            <th><small><?php echo $this->Paginator->sort('payment', 'Xu'); ?></small></th>
            <th><small><?php echo $this->Paginator->sort('role', 'Role'); ?></small></th>
            <th><small>IP</small></th>
            <th><small><?php echo $this->Paginator->sort('created'); ?></small></th>
        </tr>
        <?php
        if(!empty($users))
        foreach ($users as $user) {
            ?>
            <tr>
                <td>
                    <?php echo $user['User']['id']; ?>
                </td>
                <td>
                    <?php echo h($user['User']['username']); ?>
                </td>
                <td>
                    <?php echo h($user['Game']['title']); ?>
                </td>
                <td>
                    <?php
                    if ($user['User']['active'])
                        echo "<span style='color:green'>Active</span>";
                    else
                        echo "<span style='color:red'>Deactive</span>";
                    ?>
                </td>

                <td><?php echo $user['User']['payment'] ?></td>
                <td><?php echo $user['User']['role'] ?></td>


                <td><?php echo $user['LogLogin']['ip'] ?></td>

                <td> <?php echo $this->Time->timeAgoInWords($user['LogLogin']['created']); ?> </td>
            </tr>
            <?php } ?>
    </table>
    <?php
    echo $this->Paginator->counter(array(
        'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
    ));
    ?>
    <?php echo $this->element('paging'); ?>
</div>