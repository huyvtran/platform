<?php
$this->extend('/Common/blank');
?>
<div class="view">
	<h3>Acl Manager</h3>
	<p>This plugin allows you to easily manage your permissions. To use it you need to set up your Acl environment.</p>
	<p>Note: This plugin has only been designed to work with Actions as authorizer ($this->Auth->autorize = 'Actions').</p>
	<p>&nbsp;</p>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Manage permissions'), array('action' => 'permissions')); ?></li>
		<li><?php echo $this->Html->link(__('Update ACOs'), array('action' => 'update_acos')); ?></li>
	</ul>
</div>
