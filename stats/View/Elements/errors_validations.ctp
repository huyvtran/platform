<?php
if (empty($this->validationErrors[$model])) {
	return;
}
$output = '';
foreach($this->validationErrors[$model] as $error) {
	$output .= str_replace(':error:', current($error), $template);
}
echo $output;
?>