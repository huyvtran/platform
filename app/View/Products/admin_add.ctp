<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Admin Add Product</h3>
<div class="products form row">
	<div class='span4'>
<?php
	echo $this->Form->create('Product', array('type' => 'file'));
	if ($this->action == 'admin_edit') {
		echo $this->Form->input('id');
	}
	echo $this->Form->input('title', array('label' => 'Tiêu đề'));
	echo $this->Form->input('description', array('type' => 'textarea', 'label' => 'Mô tả'));

	echo $this->Form->input('productid', array('label' => 'Product ID'));
	echo $this->Form->input('appleid', array('label' => 'Apple ID <span class="muted">(Optional)</span>'));
	echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
?>
	</div>
	<div class='span3'>
<?php
    echo $this->Form->input('game_id', array(
        'empty' => '-- All Games --',
        'value' => empty($this->request->data['Game']['id']) ? false : $this->request->data['Game']['id'],
    )); echo "<br/>";
    echo $this->Form->input('chanel', array(
        'empty' => '-- All Chanel --',
        'value' => empty($this->request->data['Product']['chanel']) ? false : $this->request->data['Product']['chanel'],
    )); echo "<br/>";
	echo $this->Form->input('price', array('type' => 'text', 'label' => 'Giá trên website ($)'));
	echo $this->Form->input('platform_price', array('type' => 'text', 'label' => 'Platform Price'));
?>
	</div>
<?php
echo $this->Form->end();
?>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>

		<li><?php echo $this->Html->link('List Products', array('action' => 'index')); ?></li>
	</ul>
</div>
