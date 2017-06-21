<?php
$this->extend('/Common/blank');
?>
<style type='text/css'>
	pre {
		font-family: monospace;
	}
</style>
<?php
$this->extend('/Common/blank');

echo '<div class="row">';
echo '<div class="span12 btn-group">';
$Folder = new Folder(TMP . 'logs' . DS);

$logs = array( 'info.log', 'payment.log', 'user.log', 'debug.log', 'error.log');
$logs = array_unique(array_merge($logs, $Folder->find(".*\.log")));

foreach($logs as $log) {
	if (	strpos($log, 'resque') !== false
		&&	$log !== 'resque-worker-error.log'
		&&	$log !== 'resque-' . date('Y-m-d') . '.log'
	) {
		continue;
	}
	echo $this->Html->link(ucfirst($log), array('controller' => 'administrators', 'action' => 'readLog', 'admin' => true, $log), array('class' => 'btn'));
}
echo '</div><br/><br/>';


echo '<div class="span12">';
if (!empty($this->request->params['pass'][0])) {
	if (empty($content)) {
		echo '<em>File log không có dữ liệu</em>';
	} else {
		echo '<form class="form-inline">';
		echo '<input name="lines" type="text"/>';
		echo '<input type="submit" class="btn" />';
		echo '</form>';
		$start = microtime(true);
		echo '<pre>';
		$content = preg_replace("/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/", "<strong style='color:red'>$1</strong>", $content);
		echo preg_replace("/Request URL/", "<strong>Request URL</strong>", $content);
		echo '</pre>';
	}
} else {
	echo '<em>Choose a file log to read</em>';
}
echo '</div>';
echo '</div>';
?>