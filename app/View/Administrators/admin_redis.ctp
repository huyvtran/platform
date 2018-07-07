<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'>Redis</h3>
<dl>
<?php foreach ($result as $server => $keys) {?>
	<dt>Server: <?php echo $server; ?> </dt>

	<table cellpadding="0" cellspacing="0" class="table">
		<thead>
		<tr>
			<th>Type</th>
			<th>Value</th>
			<th>Key</th>
		</tr>
		</thead>
		<?php foreach ($keys as $key => $value) {?>
			<?php
				$link = false;
				if(!empty($value['func']) && $value['func'] != 'incr') {
					$link = $this->Html->url(array(
						'controller' => 'Administrators',
						'action' => 'redis_detail',
						'server' => $server,
						'key' => $key,
						'type' => $value['func']
					));
				}
			?>
			<tr>
				<td> <?php echo $value['type']; ?> </td>
				<td> <?php if( !is_array($value['count']) ) echo $value['count']; ?> </td>
				<td>
					<?php if($link){?>
						<a href="<?php echo $link; ?>" target="_blank"> <?php echo $key; ?> </a>
					<?php }else{ ?>
						<?php echo $key; ?>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>

<?php } ?>
<dl>