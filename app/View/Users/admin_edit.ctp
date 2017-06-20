<?php
$this->extend('/Common/blank');
?>
<div class="users form">
      <?php echo $this->Form->create('User', array('class' => 'simple')); ?>
      <fieldset>
            <legend>Add User</legend>
            <?php
            echo $this->Form->input('id');
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            if ($this->request->query('action') == 'changepassword') {
                  echo $this->Form->input('password');
            }
            echo $this->Form->input('description');
            echo $this->Form->input('role',array(
                  'type' => 'select',
                  'options' => array(
                      'options' => array(
                          'Admin'   => 'Admin',
                          'Content'   => 'Content Manager',
                          'Marketing' => 'Marketing',
                          'Developer' => 'Developer',
                          'Distributor' => 'Distributor',
                          'User'    => 'User',
                          'Guest'   => 'Guest'
                      )
                  ),
                  'value' => $this->request->data['User']['role']
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
