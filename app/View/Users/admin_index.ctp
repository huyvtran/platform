<?php
$this->extend('/Common/blank');
App::import('Lib', 'RedisCake');
$Redis = new RedisCake('action_count');
?>
<div class="col-md-12">
    <div class="box box-primary" id="box-online-by-server">
        <div class="box-header with-border">
            <h3 class="box-title">Filter</h3>
        </div>
        <?php echo $this->Form->create('User', array('action' => 'index', 'class' => 'form-horizontal')); ?>
        <div class="box-body">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="col-md-4">
                        <?php echo $this->Form->input('id', array('type' => 'text', 'class' => 'form-control', 'required' => false)); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $this->Form->input('username', array('class' => 'form-control', 'required' => false)); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $this->Form->input('email', array('class' => 'form-control', 'required' => false, 'type' => 'text')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <?php echo $this->Form->input('account_id', array('type' => 'text', 'class' => 'form-control', 'required' => false, 'label' => 'Account ID')); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $this->Form->input('facebook_uid', array('class' => 'form-control', 'required' => false)); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo '<label>Staff only' . $this->Form->input('staff', array('type' => 'checkbox', 'label' => false)) . '</label>'; ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="pull-right">
                        <?php echo $this->Form->submit('Search', array('class' => 'btn')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <em>(1) fields was required together if you use one of these fields while search </em>
                <p><span style="color:red">Note : </span>Fileds username and email can use "nickname%" to search user have email "nickname123@anything.com"</p>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<div class="col-md-12">
    <div class="box box-primary" id="box-online-by-server">
        <div class="box-header with-border">
            <h3 class="box-title">List user</h3>
            <div class="box-tools pull-right">
                <?php echo $this->element('paging'); ?>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-hover responsive">
                <tr>
                    <th><small><?php echo $this->Paginator->sort('id'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('username'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('email'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('created'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('last_action'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('email_verified', 'Email Verified'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('active', 'Active'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('facebook_uid', 'Facebook ID'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('payment', 'Xu'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('role', 'Role'); ?></small></th>
                    <th><small><?php echo $this->Paginator->sort('phone', 'Phone'); ?></small></th>
                    <th><small>Descriptions</small></th>
                    <th><small>Country</small></th>
                    <th class="actions"><small>Actions</small></th>
                </tr>
                <?php
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
                        <?php echo h($user['User']['email']); ?>
                    </td>
                    <td>
                        <?php echo $this->Time->timeAgoInWords($user['User']['created']); ?>
                    </td>
                    <td>
                        <?php echo $this->Time->timeAgoInWords($user['User']['last_action']); ?>
                    </td>
                    <td>
                        <?php
                        if ($user['User']['email_verified'])
                        echo "<span style='color:green'>Verified</span>";
                        else
                        echo "<span style='color:red'>No</span>";
                        ?>
                        <td>
                            <?php
                            if ($user['User']['active'])
                            echo "<span style='color:green'>Active</span>";
                            else
                            echo "<span style='color:red'>Deactive</span>";
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($user['User']['facebook_uid'])
                            echo "<span style='color:green'>" . $user['User']['facebook_uid'] . "</span>";
                            else
                            echo "<span style='color:red'>No</span>";
                            ?>
                        </td>
                        <td>
                            <?php echo $user['User']['payment'] ?>
                        </td>
                        <td>
                            <?php echo $user['User']['role'] ?>
                        </td>
                        <td>
                            <?php echo $user['User']['phone'] ?>
                        </td>
                        <td>
                            <?php echo $user['User']['description'] ?>
                        </td>
                        <td>
                            <?php echo $user['User']['country_code'] ?>
                        </td>
                        <td class="actions btn-group">
                            <?php echo $this->Html->link('View', '/admin/users/view/' . $user['User']['id'], array('class' => 'btn btn-mini')); ?>
                            <?php  ?>
                            <div class="btn-group">
                                <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                                  Others <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                <li>
                                    <?php echo $this->Html->link('Add Permissions', '/admin/permissions/add?user_id=' . $user['User']['id']); ?>
                                </li>
                                <li>
                                    <?php
                                    $key = 'reset_password_' . $user['User']['id'];
                                    if( $Redis->exists($key) ){
                                    echo $this->Html->link('Reset Password', '/admin/users/reset_password/' . $user['User']['id']);
                                }else{
                                echo $this->Html->link('Change Password', '/admin/users/editContent/' . $user['User']['id']);
                            }
                            ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link('Edit', '/admin/users/edit/' . $user['User']['id']); ?>
                        </li>
                        <?php
                        if (!empty($user['User']['active'])) {
                        ?>
                        <li>
                            <?php echo $this->Html->link('Deactive', '/admin/users/deactive/' . $user['User']['id']); ?>
                        </li>
                        <?php
                    } else {
                    ?>
                    <li>
                        <?php echo $this->Html->link('Active', '/admin/users/deactive/' . $user['User']['id'] . '/1'); ?>
                    </li>
                    <?php
                }
                ?>
            </li>
        </ul>
    </div>
</td>
</tr>
<?php
if (!empty($user['Game'])) {
echo '<tr>';
echo '<td colspan="10"><b>Played: ';
foreach($user['Game'] as $game) {
echo $game['title'] . ' ' . $game['os'] . ' - ';
}
echo '</b></td>';
echo '</tr>';
}
?>
<?php
}
?>
</table>
<div class="box-footer">
    <?php
    echo $this->Paginator->counter(array(
    'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
    ));
    ?>
    <?php echo $this->element('paging'); ?>
</div>
</div>
</div>
</div>
