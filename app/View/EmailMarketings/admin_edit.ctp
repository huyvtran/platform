<?php
if (!$this->request->query('template') && empty($email['EmailMarketing']['body'])) {
$this->extend('/Common/blank'); 
?>

<h2><?php echo 'Admin Add Email Marketing'; ?></h2>
<div class='row'> 
	<div class='span12'>
	<strong>Chọn template : </strong>
	<ul>
	<?php
	$Folder = new Folder();

	$Folder->cd($directoryTemp);
	$files = $Folder->findRecursive();
	foreach($files as $file) {
		echo '<li>' . $this->Html->link(basename($file), array(
			$this->request->params['pass'][0],
			'?' => array(
				'template' => preg_replace("/\\.[^.\\s]{3,4}$/", "", basename($file)),
			)));
		echo "</li>";
	}
	?>
	</ul>
	</div>
</div>
 
<?php
} else {
	echo $body;
}
?>

