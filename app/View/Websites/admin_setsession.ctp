<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Choose website, you want to go: </h3>
<div class='row'>
	<div class='span12'>
	<?php
	if ($this->Session->read('Admin.website.url')) {
		echo '<div class="lead text-success">Current Session: <strong>' . $this->Session->read('Admin.website.url') . '</strong></div>';
	}
	?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Title</th>
				<th>Url</th>
				<th>Lang</th>
				<th>Choose site</th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($websites as $key => $value) {
		echo '<tr>';
		echo '<td>' . $value['Website']['title'] . '</td>';
		echo '<td>' . $value['Website']['url'] . '</td>';
		echo '<td>' . $value['Website']['lang'] . '</td>';
		$currentId = $this->Session->read('Admin.website.id');
		echo '<td>';
		if ($currentId == $value['Website']['id']) {
			echo 'Using this site';
		} else {
			echo $this->Form->postLink('Choose', array('controller' => 'websites', 'action' => 'setsession', $value['Website']['id']));
		}
		echo '<td>';
		echo '</tr>';
	}
	?>
		</tbody>
	</table>
	</div>
</div>