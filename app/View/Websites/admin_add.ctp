<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Admin Add Website</h3>
<div class="websites form row">
	<div class='span4'>
<?php
	echo $this->Form->create('Website', array('type' => 'file'));
	if ($this->action == 'admin_edit') {
		echo $this->Form->input('id');
	}
	echo $this->Form->input('title');
	echo $this->Form->input('url', array('label' => 'URL'));
	echo $this->Form->input('theme');
	echo $this->Form->input('url2', array('label' => 'URL2'));
	echo $this->Form->input('theme_mobile');
	echo $this->Form->input('lang', array(
				'options' => array(
					'eng'   => 'English',
					'zh_cn' => 'Simplified Chinese',
					'zh_tw' => 'Traditional Chinese',
					'tha'   => 'Thai',
					'vie'   => 'Vie',
					'es_es' => 'Spanish',
					'ind' => 'Indo'
				),
				'empty' => '--Choose language default--'
			));
	echo $this->Form->input('published', array('label' => array('text' => 'Published')));
?>
	<div class='form-actions'>
	<?php
	echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
	echo $this->Form->end();
	?>
	</div>
	</div>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link('List Websites', array('action' => 'index')); ?></li>
	</ul>
</div>
