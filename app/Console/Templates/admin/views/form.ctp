<?php echo "<?php \$this->extend('/Common/blank'); ?>\n" ?>

<div class="<?php echo $pluralVar; ?> form row">
<div class='span12'>
<?php echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n"; ?>
	<h2 class='page-header'><?php printf("<?php echo '%s %s'; ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
<?php
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
				echo "\t\techo \$this->Form->input('{$field}');\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
			}
		}
		echo "\t?>\n";
?>
<?php
	echo "<?php echo \$this->Form->end('Submit'); ?>\n";
?>

	<div class="actions">
		<h3><?php echo "<?php echo 'Actions'; ?>"; ?></h3>
		<ul>

	<?php if (strpos($action, 'add') === false): ?>
			<li><?php echo "<?php echo \$this->Form->postLink('Delete', array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), null, sprintf('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>"; ?></li>
	<?php endif; ?>
			<li><?php echo "<?php echo \$this->Html->link('List " . $pluralHumanName . "', array('action' => 'index')); ?>"; ?></li>
	<?php
			$done = array();
			foreach ($associations as $type => $data) {
				foreach ($data as $alias => $details) {
					if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
						echo "\t\t<li><?php echo \$this->Html->link('List " . Inflector::humanize($details['controller']) . "', array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
						echo "\t\t<li><?php echo \$this->Html->link('New " . Inflector::humanize(Inflector::underscore($alias)) . "', array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
						$done[] = $details['controller'];
					}
				}
			}
	?>
		</ul>
	</div>
</div>
</div>