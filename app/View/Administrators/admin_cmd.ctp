<?php
$this->extend('/Common/blank');
echo $this->Form->create(false);
echo $this->Form->input('cmd');
echo '<input type="submit" class="btn" />';
echo $this->Form->end();
if (!empty($screen)) {
	echo '<h4> Output Command </h4>';
	echo $screen;
}
?>



<pre>
<code>
# read log from redis then save into db
php <?php echo ROOT . DS ?>app/Console/cake.php Log save
</code>
</pre>