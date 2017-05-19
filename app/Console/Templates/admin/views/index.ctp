<?php echo "<?php \$this->extend('/Common/blank'); ?>\n" ?>

<div class="<?php echo $pluralVar; ?> row">
	<div class='span12'>

	<h2><?php echo "<?php echo '{$pluralHumanName}'; ?>"; ?></h2>
	<table class="table table-striped">
	<tr>
	<?php foreach ($fields as $field): ?>
		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
	<?php endforeach; ?>
		<th class="actions"><?php echo "<?php echo 'Actions'; ?>"; ?></th>
	</tr>
	<?php
	echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
	echo "\t<tr>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			}
		}

		echo "\t\t<td class=\"btn-group\">\n";
		echo "\t\t\t<?php echo \$this->Html->link('View', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-mini')); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->link('Edit', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-mini')); ?>\n";
		echo "\t\t\t<?php echo \$this->Form->postLink('Delete', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-mini'), sprintf('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>
	<p>
	<?php echo "<?php
	echo \$this->Paginator->counter(array(
	'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
	));
	?>"; ?>
	</p>
	<div class="paging">
	<?php
	echo "<?php\n";
	echo "\t\techo \$this->element('paging');\n";
	echo "\t?>\n";
	?>
	</div>

	<div class="actions">
		<h3><?php echo "<?php echo 'Actions'; ?>"; ?></h3>
		<ul>
			<li><?php echo "<?php echo \$this->Html->link('New " . $singularHumanName . "', array('action' => 'add')); ?>"; ?></li>
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