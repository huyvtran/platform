<?php
$this->extend('/Common/blank');
?>
<div class="users form">
      <?php echo $this->Form->create('User', array('url' => '/admin/users/add', 'class' => 'simple')); ?>
      <fieldset>
            <legend>Add User</legend>
            <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('password', array('type' => 'text'));
            echo $this->Form->input('role',array(
                  'type' => 'select',
                  'options' => array(
                        'Admin' => 'Admin',
                        'MarketingAdmin' => 'MarketingAdmin',
                        'Content' => 'Content Manager',
                        'Marketing' => 'Marketing',
                        'Developer' => 'Developer',
                        'Stats' => 'View Stats',
                        'Test' => 'Test',
                        'User' => 'User',
                        'Guest' => 'Guest'
                  )
            ));
            echo '<div class="form-actions">';
            echo $this->Form->submit('Submit', array('class' => 'btn'));
            echo '</div>';
            echo $this->Form->end();
            ?>
      </fieldset>

</div>
<div class="actions">
      <ul>
            <li><?php echo $this->Html->link('List Users', array('action' => 'index')); ?></li>
      </ul>
</div>
