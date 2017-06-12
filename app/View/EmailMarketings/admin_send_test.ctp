<?php $this->extend('/Common/blank'); ?>

<div class="emailMarketings form row">
<div class="span12">
<?php echo $this->Form->create('EmailMarketing'); ?>
<?php echo 'Send this Email : <strong>"' . $email['EmailMarketing']['title'] . '"</strong> to your email'; ?>

<?php
	echo $this->Form->input('email', array('type' => 'textarea'));
	echo $this->Form->submit('Send Email', array('class' => 'btn btn-primary'));
	echo $this->Form->end();
?>
<div class="actions">
	<h3><?php echo 'Actions'; ?></h3>
	<ul>
		<li><?php echo $this->Html->link('Edit this email', array('action' => 'edit', $email['EmailMarketing']['id'])); ?> </li>
		<li><?php echo $this->Html->link('List emails', array('action' => 'index')); ?> </li>
	</ul>
</div>