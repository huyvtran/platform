<?php
$this->extend('/Common/blank');
?>
<h3 class = "page-header">
<?php
echo 'Thêm / Sửa ' . $modelClass;
?>
</h3>

<div>
<?php

echo $this->Form->create($modelClass);

if ($this->request->params['action'] == 'admin_edit'){
	echo $this->Form->input('id');
}

echo $this->Form->input('category_id', array(
	'empty' => '-- Choose Parent -- ', 'label' => 'Parent Category'
));
echo $this->Form->input('title');
echo $this->Form->input('description');
echo $this->Form->input('slug');
echo '<em>Just developer can change this slug</em>';

echo $this->Form->input('type', array(
	'type' => 'select',
	'options' => array(
		'Article' => 'Trang tin tức',
		'Help' => 'Trang hướng dẫn'
	),
	'empty' => '-- Chọn kiểu mục lục --'
));

echo '<div class ="form-actions btn-group">';
echo $this->Form->button('Submit', array(
	'type' => 'submit', 'name' => 'submit', 'value' => '1', 'class' => 'btn btn-primary'));
	
if ($this->request->params['action'] == 'admin_add'){	
echo $this->Form->button('Submit and continue', array(
	'type' => 'submit', 'name' => 'submit', 'value' => '2', 'class' => 'btn btn-primary'));
}
echo '</div>';

echo $this->Form->end();
?>
</div>
<?php
echo $this->Html->link('Add', array('action' => 'add')) . ' | '; 
echo $this->Html->link('Index', array('action' => 'index'));
?>